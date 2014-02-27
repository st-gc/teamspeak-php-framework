<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Adapter\ServerQuery;

use TeamSpeak\Constant;
use TeamSpeak\Model\String;
use TeamSpeak\Model\Signal;

use TeamSpeak\Exception\AdapterException;
use TeamSpeak\Exception\Adapter\ServerQueryException;
use TeamSpeak\Exception\NodeException;

use TeamSpeak\Node\Host;

/**
 * Provides methods to analyze and format a ServerQuery event.
 */
class Event implements \ArrayAccess
{

	/**
	 * Stores the event type.
	 *
	 * @var String
	 */
	protected $type = null;

	/**
	 * Stores the event data.
	 *
	 * @var array
	 */
	protected $data = null;

	/**
	 * Stores the event data as an unparsed string.
	 *
	 * @var String
	 */
	protected $mesg = null;

	/**
	 * Creates a new TeamSpeak3_Adapter_ServerQuery_Event object.
	 *
	 * @param String $evt
	 * @param Host   $con
	 *
	 * @throws AdapterException
	 * @return Event
	 */
	public function __construct( String $evt, Host $con = null )
	{

		if ( !$evt->startsWith( Constant::EVENT ) ) {
			throw new AdapterException( "invalid notification event format" );
		}

		list( $type, $data ) = $evt->split( Constant::SEPARATOR_CELL, 2 );

		if ( empty( $data ) ) {
			throw new AdapterException( "invalid notification event data" );
		}

		$fake = new String( Constant::ERROR . Constant::SEPARATOR_CELL . "id" . Constant::SEPARATOR_PAIR . 0 . Constant::SEPARATOR_CELL . "msg" . Constant::SEPARATOR_PAIR . "ok" );
		$repl = new Reply( array( $data, $fake ), $type );

		$this->type = $type->substr( strlen( Constant::EVENT ) );
		$this->data = $repl->toList();
		$this->mesg = $data;

		Signal::getInstance()->emit( "notifyEvent", $this, $con );
		Signal::getInstance()->emit( "notify" . ucfirst( $this->type ), $this, $con );
	}

	/**
	 * Returns the event data array.
	 *
	 * @return array
	 */
	public function getData()
	{

		return $this->data;
	}

	/**
	 * Returns the event data as an unparsed string.
	 *
	 * @return String
	 */
	public function getMessage()
	{

		return $this->mesg;
	}

	/**
	 * @ignore
	 */
	public function offsetUnset( $offset )
	{

		unset( $this->data[ $offset ] );
	}

	/**
	 * @ignore
	 */
	public function __get( $offset )
	{

		return $this->offsetGet( $offset );
	}

	/**
	 * @ignore
	 */
	public function __set( $offset, $value )
	{

		$this->offsetSet( $offset, $value );
	}

	/**
	 * @ignore
	 */
	public function offsetGet( $offset )
	{

		if ( !$this->offsetExists( $offset ) ) {
			throw new ServerQueryException( "invalid parameter", 0x602 );
		}

		return $this->data[ $offset ];
	}

	/**
	 * @ignore
	 */
	public function offsetExists( $offset )
	{

		return array_key_exists( $offset, $this->data ) ? true : false;
	}

	/**
	 * @ignore
	 */
	public function offsetSet( $offset, $value )
	{

		throw new NodeException( "event '" . $this->getType() . "' is read only" );
	}

	/**
	 * Returns the event type string.
	 *
	 * @return String
	 */
	public function getType()
	{

		return $this->type;
	}
}
