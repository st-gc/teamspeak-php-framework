<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Adapter;

use TeamSpeak\Constant;
use TeamSpeak\Exception;
use TeamSpeak\Helper\Profiler;
use TeamSpeak\Model\Signal;

use TeamSpeak\Exception\AdapterException;
use TeamSpeak\Exception\Adapter\ServerQueryException;

use TeamSpeak\Model\String;

use TeamSpeak\Node\AbstractNode;

use TeamSpeak\Adapter\ServerQuery\Event;
use TeamSpeak\Adapter\ServerQuery\Reply;

use TeamSpeak\Transport\AbstractTransport;

use TeamSpeak\Node\Host;

/**
 * Provides low-level methods for ServerQuery communication with a TeamSpeak 3 Server.
 */
class ServerQueryAdapter extends AbstractAdapter
{

	/**
	 * Stores a singleton instance of the active TeamSpeak3_Node_Host object.
	 *
	 * @var Host
	 */
	protected $host = null;

	/**
	 * Stores the timestamp of the last command.
	 *
	 * @var integer
	 */
	protected $timer = null;

	/**
	 * Number of queries executed on the server.
	 *
	 * @var integer
	 */
	protected $count = 0;

	/**
	 * Stores an array with unsupported commands.
	 *
	 * @var array
	 */
	protected $block = array( "help" );

	/**
	 * The TeamSpeak3_Adapter_ServerQuery destructor.
	 *
	 * @return void
	 */
	public function __destruct()
	{

		if ( $this->getTransport() instanceof AbstractTransport && $this->transport->isConnected() ) {
			try {
				$this->request( "quit" );
			} catch ( Exception $e ) {
				return;
			}
		}
	}

	/**
	 * Sends a prepared command to the server and returns the result.
	 *
	 * @param  string  $cmd
	 * @param  boolean $throw
	 *
	 * @throws AdapterException
	 * @return Reply
	 */
	public function request( $cmd, $throw = true )
	{

		$query = String::factory( $cmd )->section( Constant::SEPARATOR_CELL );

		if ( strstr( $cmd, "\r" ) || strstr( $cmd, "\n" ) ) {
			throw new AdapterException( "illegal characters in command '" . $query . "'" );
		} elseif ( in_array( $query, $this->block ) ) {
			throw new ServerQueryException( "command not found", 0x100 );
		}

		Signal::getInstance()->emit( "serverqueryCommandStarted", $cmd );

		$this->getProfiler()->start();
		$this->getTransport()->sendLine( $cmd );
		$this->timer = time();
		$this->count++;

		$rpl = array();

		do {
			$str    = $this->getTransport()->readLine();
			$rpl[ ] = $str;
		} while ( $str instanceof String && $str->section( Constant::SEPARATOR_CELL ) != Constant::ERROR );

		$this->getProfiler()->stop();

		$reply = new Reply( $rpl, $cmd, $this->getHost(), $throw );

		Signal::getInstance()->emit( "serverqueryCommandFinished", $cmd, $reply );

		return $reply;
	}

	/**
	 * Waits for the server to send a notification message and returns the result.
	 *
	 * @throws AdapterException
	 * @return Event
	 */
	public function wait()
	{

		if ( $this->getTransport()->getConfig( "blocking" ) ) {
			throw new AdapterException( "only available in non-blocking mode" );
		}

		do {
			$evt = $this->getTransport()->readLine();
		} while ( $evt instanceof String && !$evt->section( Constant::SEPARATOR_CELL )->startsWith( Constant::EVENT ) );

		return new Event( $evt, $this->getHost() );
	}

	/**
	 * Uses given parameters and returns a prepared ServerQuery command.
	 *
	 * @param  string $cmd
	 * @param  array  $params
	 *
	 * @return string
	 */
	public function prepare( $cmd, array $params = array() )
	{

		$args  = array();
		$cells = array();

		foreach ( $params as $ident => $value ) {
			$ident = is_numeric( $ident ) ? "" : strtolower( $ident ) . Constant::SEPARATOR_PAIR;

			if ( is_array( $value ) ) {
				$value = array_values( $value );

				for ( $i = 0; $i < count( $value ); $i++ ) {
					if ( $value[ $i ] === null ) {
						continue;
					} elseif ( $value[ $i ] === false ) {
						$value[ $i ] = 0x00;
					} elseif ( $value[ $i ] === true ) {
						$value[ $i ] = 0x01;
					} elseif ( $value[ $i ] instanceof AbstractNode ) {
						$value[ $i ] = $value[ $i ]->getId();
					}

					$cells[ $i ][ ] = $ident . String::factory( $value[ $i ] )->escape()->toUtf8();
				}
			} else {
				if ( $value === null ) {
					continue;
				} elseif ( $value === false ) {
					$value = 0x00;
				} elseif ( $value === true ) {
					$value = 0x01;
				} elseif ( $value instanceof AbstractNode ) {
					$value = $value->getId();
				}

				$args[ ] = $ident . String::factory( $value )->escape()->toUtf8();
			}
		}

		foreach ( array_keys( $cells ) as $ident ) {
			$cells[ $ident ] = implode(
				Constant::SEPARATOR_CELL,
				$cells[ $ident ]
			);
		}

		if ( count( $args ) ) {
			$cmd .= " " . implode( Constant::SEPARATOR_CELL, $args );
		}
		if ( count( $cells ) ) {
			$cmd .= " " . implode( Constant::SEPARATOR_LIST, $cells );
		}

		return trim( $cmd );
	}

	/**
	 * Returns the timestamp of the last command.
	 *
	 * @return integer
	 */
	public function getQueryLastTimestamp()
	{

		return $this->timer;
	}

	/**
	 * Returns the number of queries executed on the server.
	 *
	 * @return integer
	 */
	public function getQueryCount()
	{

		return $this->count;
	}

	/**
	 * Returns the total runtime of all queries.
	 *
	 * @return mixed
	 */
	public function getQueryRuntime()
	{

		return $this->getProfiler()->getRuntime();
	}

	/**
	 * Returns the TeamSpeak3_Node_Host object of the current connection.
	 *
	 * @return TeamSpeak3_Node_Host
	 */
	public function getHost()
	{

		if ( $this->host === null ) {
			$this->host = new Host( $this );
		}

		return $this->host;
	}

	/**
	 * Connects the TeamSpeak3_Transport_Abstract object and performs initial actions on the remote
	 * server.
	 *
	 * @throws TeamSpeak3_Adapter_Exception
	 * @return void
	 */
	protected function syn()
	{

		$this->initTransport( $this->options );
		$this->transport->setAdapter( $this );

		Profiler::init( spl_object_hash( $this ) );

		if ( !$this->getTransport()->readLine()->startsWith( Constant::READY ) ) {
			throw new AdapterException( "invalid reply from the server" );
		}

		Signal::getInstance()->emit( "serverqueryConnected", $this );
	}
}
