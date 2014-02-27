<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Helper;

use TeamSpeak\Helper\Profiler\Timer;


/**
 * Helper class for profiler handling.
 */
class Profiler
{

	/**
	 * Stores various timers for code profiling.
	 *
	 * @var Timer[]
	 */
	protected static $timers = array();

	/**
	 * Starts a timer.
	 *
	 * @param  string $name
	 *
	 * @return void
	 */
	public static function start( $name = "default" )
	{

		if ( array_key_exists( $name, self::$timers ) ) {
			self::$timers[ $name ]->start();
		} else {
			self::$timers[ $name ] = new Timer( $name );
		}
	}

	/**
	 * Stops a timer.
	 *
	 * @param  string $name
	 *
	 * @return void
	 */
	public static function stop( $name = "default" )
	{

		if ( !array_key_exists( $name, self::$timers ) ) {
			self::init( $name );
		}

		self::$timers[ $name ]->stop();
	}

	/**
	 * Inits a timer.
	 *
	 * @param  string $name
	 *
	 * @return void
	 */
	public static function init( $name = "default" )
	{

		self::$timers[ $name ] = new Timer( $name );
	}

	/**
	 * Returns a timer.
	 *
	 * @param  string $name
	 *
	 * @return Timer
	 */
	public static function get( $name = "default" )
	{

		if ( !array_key_exists( $name, self::$timers ) ) {
			self::init( $name );
		}

		return self::$timers[ $name ];
	}
}
