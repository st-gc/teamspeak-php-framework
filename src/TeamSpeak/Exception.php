<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak;

use TeamSpeak\Model\String;
use TeamSpeak\Model\Signal;

/**
 * Enhanced exception class for TeamSpeak objects.
 */
class Exception extends \Exception
{

	/**
	 * Stores custom error messages.
	 *
	 * @var array
	 */
	protected static $messages = array();

	/**
	 * The Exception constructor.
	 *
	 * @param  string  $msg
	 * @param  integer $code
	 *
	 * @return \TeamSpeak\Exception
	 */
	public function __construct( $msg, $code = 0x00 )
	{

		parent::__construct( $msg, $code );

		if ( array_key_exists( (int)$code, self::$messages ) ) {
			$this->message = $this->prepareCustomMessage( self::$messages[ intval( $code ) ] );
		}

		Signal::getInstance()->emit( "errorException", $this );
	}

	/**
	 * Prepares a custom error message by replacing pre-defined signs with given values.
	 *
	 * @param \TeamSpeak\Model\String $msg
	 *
	 * @return String
	 */
	protected function prepareCustomMessage( String $msg )
	{

		$args = array(
			"code" => $this->getCode(),
			"mesg" => $this->getMessage(),
			"line" => $this->getLine(),
			"file" => $this->getFile(),
		);

		return $msg->arg( $args )->toString();
	}

	/**
	 * Registers a custom error message to $code.
	 *
	 * @param  integer $code
	 * @param  string  $msg
	 *
	 * @throws Exception
	 * @return void
	 */
	public static function registerCustomMessage( $code, $msg )
	{

		if ( array_key_exists( (int)$code, self::$messages ) ) {
			throw new self( "custom message for code 0x" . strtoupper( dechex( $code ) ) . " is already registered" );
		}

		if ( !is_string( $msg ) ) {
			throw new self( "custom message for code 0x" . strtoupper( dechex( $code ) ) . " must be a string" );
		}

		self::$messages[ (int)$code ] = new String( $msg );
	}

	/**
	 * Unregisters a custom error message from $code.
	 *
	 * @param  integer $code
	 *
	 * @throws Exception
	 * @return void
	 */
	public static function unregisterCustomMessage( $code )
	{

		if ( !array_key_exists( (int)$code, self::$messages ) ) {
			throw new self( "custom message for code 0x" . strtoupper( dechex( $code ) ) . " is not registered" );
		}

		unset( self::$messages[ intval( $code ) ] );
	}

	/**
	 * Returns the class from which the exception was thrown.
	 *
	 * @return string
	 */
	public function getSender()
	{

		$trace = $this->getTrace();

		return ( isset( $trace[ 0 ][ "class" ] ) ) ? $trace[ 0 ][ "class" ] : "{main}";
	}
}
