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


use TeamSpeak\Exception\Adapter\TsDnsException;
use TeamSpeak\Helper\Profiler;
use TeamSpeak\Model\Signal;

use TeamSpeak\Transport\AbstractTransport;

/**
 * Provides methods to query a TSDNS server.
 */
class TsDnsAdapter extends AbstractAdapter
{

	/**
	 * The TCP port number used by any TSDNS server.
	 *
	 * @var integer
	 */
	protected $default_port = 41144;

	/**
	 * Connects the TeamSpeak3_Transport_Abstract object and performs initial actions on the remote
	 * server.
	 *
	 * @return void
	 */
	public function syn()
	{

		if ( !isset( $this->options[ "port" ] ) || empty( $this->options[ "port" ] ) ) {
			$this->options[ "port" ] = $this->default_port;
		}

		$this->initTransport( $this->options );
		$this->transport->setAdapter( $this );

		Profiler::init( spl_object_hash( $this ) );

		Signal::getInstance()->emit( "tsdnsConnected", $this );
	}

	/**
	 * The TeamSpeak3_Adapter_FileTransfer destructor.
	 *
	 * @return void
	 */
	public function __destruct()
	{

		if ( $this->getTransport() instanceof AbstractTransport && $this->getTransport()->isConnected() ) {
			$this->getTransport()->disconnect();
		}
	}

	/**
	 * Queries the TSDNS server for a specified virtual hostname and returns the result.
	 *
	 * @param  string $tsdns
	 *
	 * @throws TsDnsException
	 * @return String
	 */
	public function resolve( $tsdns )
	{

		$this->getTransport()->sendLine( $tsdns );
		$repl = $this->getTransport()->readLine();
		$this->getTransport()->disconnect();

		if ( $repl->section( ":", 0 )->toInt() == 404 ) {
			throw new TsDnsException( "unable to resolve TSDNS hostname (" . $tsdns . ")" );
		}

		Signal::getInstance()->emit( "tsdnsResolved", $tsdns, $repl );

		return $repl;
	}
}
