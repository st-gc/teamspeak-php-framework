<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Model;

use TeamSpeak\Exception\Model\SignalException;
use TeamSpeak\Model\Signal\Handler;

/**
 * @class Signal
 * @brief Helper class for signal slots.
 */
class Signal
{

	/**
	 * Stores the Signal object.
	 *
	 * @var Signal
	 */
	protected static $instance = null;

	/**
	 * Stores subscribed signals and their slots.
	 *
	 * @var array
	 */
	protected $sigslots = array();

	/**
	 * Returns a singleton instance of Signal.
	 *
	 * @return Signal
	 */
	public static function getInstance()
	{

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Emits a signal with a given set of parameters.
	 *
	 * @param  string $signal
	 * @param  mixed  $params
	 *
	 * @return mixed
	 */
	public function emit( $signal, $params = null )
	{

		if ( !$this->hasHandlers( $signal ) ) {
			return;
		}

		if ( !is_array( $params ) ) {
			$params = func_get_args();
			$params = array_slice( $params, 1 );
		}

		foreach ( $this->sigslots[ $signal ] as $slot ) {
			$return = $slot->call( $params );
		}

		return $return;
	}

	/**
	 * Returns TRUE there are slots subscribed for a specified signal.
	 *
	 * @param  string $signal
	 *
	 * @return boolean
	 */
	public function hasHandlers( $signal )
	{

		return empty( $this->sigslots[ $signal ] ) ? false : true;
	}

	/**
	 * Subscribes to a signal and returns the signal handler.
	 *
	 * @param  string $signal
	 * @param  mixed  $callback
	 *
	 * @return Handler
	 */
	public function subscribe( $signal, $callback )
	{

		if ( empty( $this->sigslots[ $signal ] ) ) {
			$this->sigslots[ $signal ] = array();
		}

		$index = $this->getCallbackHash( $callback );

		if ( !array_key_exists( $index, $this->sigslots[ $signal ] ) ) {
			$this->sigslots[ $signal ][ $index ] = new Handler( $signal, $callback );
		}

		return $this->sigslots[ $signal ][ $index ];
	}

	/**
	 * Generates a MD5 hash based on a given callback.
	 *
	 * @param  mixed $callback
	 *
	 * @throws
	 * @internal param $string
	 *
	 * @return string
	 */
	public function getCallbackHash( $callback )
	{
		if ( !is_callable( $callback, true, $callable_name ) ) {
			throw new SignalException( "invalid callback specified" );
		}

		return md5( $callable_name );
	}

	/**
	 * Unsubscribes from a signal.
	 *
	 * @param  string $signal
	 * @param  mixed  $callback
	 *
	 * @return void
	 */
	public function unsubscribe( $signal, $callback = null )
	{

		if ( !$this->hasHandlers( $signal ) ) {
			return;
		}

		if ( $callback !== null ) {
			$index = md5( serialize( $callback ) );

			if ( !array_key_exists( $index, $this->sigslots[ $signal ] ) ) {
				return;
			}

			unset( $this->sigslots[ $signal ][ $index ] );
		} else {
			unset( $this->sigslots[ $signal ] );
		}
	}

	/**
	 * Returns all registered signals.
	 *
	 * @return array
	 */
	public function getSignals()
	{

		return array_keys( $this->sigslots );
	}

	/**
	 * Returns all slots for a specified signal.
	 *
	 * @param  string $signal
	 *
	 * @return array
	 */
	public function getHandlers( $signal )
	{

		if ( !$this->hasHandlers( $signal ) ) {
			return $this->sigslots[ $signal ];
		}

		return array();
	}

	/**
	 * Clears all slots for a specified signal.
	 *
	 * @param  string $signal
	 *
	 * @return void
	 */
	public function clearHandlers( $signal )
	{

		if ( !$this->hasHandlers( $signal ) ) {
			unset( $this->sigslots[ $signal ] );
		}
	}
}
