<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Helper\Profiler;

/**
 * Helper class providing profiler timers.
 */
class Timer
{

	/**
	 * Indicates whether the timer is running or not.
	 *
	 * @var boolean
	 */
	protected $running = false;

	/**
	 * Stores the timestamp when the timer was last started.
	 *
	 * @var integer
	 */
	protected $started = 0;

	/**
	 * Stores the timer name.
	 *
	 * @var string
	 */
	protected $name = null;

	/**
	 * Stores various information about the server environment.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * The Timer constructor.
	 *
	 * @param  string $name
	 *
	 * @return Timer
	 */
	public function __construct( $name )
	{

		$this->name = (string)$name;

		$this->data[ "runtime" ] = 0;
		$this->data[ "realmem" ] = 0;
		$this->data[ "emalloc" ] = 0;

		$this->start();
	}

	/**
	 * Starts the timer.
	 *
	 * @return void
	 */
	public function start()
	{

		if ( $this->isRunning() ) {
			return;
		}

		$this->data[ "realmem_start" ] = memory_get_usage( true );
		$this->data[ "emalloc_start" ] = memory_get_usage();

		$this->started = microtime( true );
		$this->running = true;
	}

	/**
	 * Returns TRUE if the timer is running.
	 *
	 * @return boolean
	 */
	public function isRunning()
	{

		return $this->running;
	}

	/**
	 * Return the timer runtime.
	 *
	 * @return mixed
	 */
	public function getRuntime()
	{

		if ( $this->isRunning() ) {
			$this->stop();
			$this->start();
		}

		return $this->data[ "runtime" ];
	}

	/**
	 * Stops the timer.
	 *
	 * @return void
	 */
	public function stop()
	{

		if ( !$this->isRunning() ) {
			return;
		}

		$this->data[ "runtime" ] += microtime( true ) - $this->started;
		$this->data[ "realmem" ] += memory_get_usage( true ) - $this->data[ "realmem_start" ];
		$this->data[ "emalloc" ] += memory_get_usage() - $this->data[ "emalloc_start" ];

		$this->started = 0;
		$this->running = false;
	}

	/**
	 * Returns the amount of memory allocated to PHP in bytes.
	 *
	 * @param  boolean $realmem
	 *
	 * @return integer
	 */
	public function getMemUsage( $realmem = false )
	{

		if ( $this->isRunning() ) {
			$this->stop();
			$this->start();
		}

		return ( $realmem !== false ) ? $this->data[ "realmem" ] : $this->data[ "emalloc" ];
	}
}
