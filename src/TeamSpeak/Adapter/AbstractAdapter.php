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

use TeamSpeak\Exception\AdapterException;
use TeamSpeak\Helper\Profiler;
use TeamSpeak\Helper\Profiler\Timer;
use TeamSpeak\Transport\AbstractTransport;

/**
 * @class AbstractAdapter
 * @brief Provides low-level methods for concrete adapters to communicate with a TeamSpeak 3 Server.
 */
abstract class AbstractAdapter
{

	/**
	 * Stores user-provided options.
	 *
	 * @var array
	 */
	protected $options = null;

	/**
	 * Stores an AbstractTransport object.
	 *
	 * @var AbstractTransport
	 */
	protected $transport = null;

	/**
	 * The AbstractAdapter constructor.
	 *
	 * @param  array $options
	 *
	 * @return AbstractAdapter
	 */
	public function __construct( array $options )
	{

		$this->options = $options;

		if ( $this->transport === null ) {
			$this->syn();
		}
	}

	/**
	 * Connects the AbstractTransport object and performs initial actions on the remote
	 * server.
	 *
	 * @throws AdapterException
	 * @return void
	 */
	abstract protected function syn();

	/**
	 * The AbstractAdapter destructor.
	 *
	 * @return void
	 */
	abstract public function __destruct();

	/**
	 * Commit pending data.
	 *
	 * @return array
	 */
	public function __sleep()
	{

		return array( "options" );
	}

	/**
	 * Reconnects to the remote server.
	 *
	 * @return void
	 */
	public function __wakeup()
	{

		$this->syn();
	}

	/**
	 * Returns the profiler timer used for this connection adapter.
	 *
	 * @return Timer
	 */
	public function getProfiler()
	{

		return Profiler::get( spl_object_hash( $this ) );
	}

	/**
	 * Returns the hostname or IPv4 address the underlying AbstractTransport object
	 * is connected to.
	 *
	 * @return string
	 */
	public function getTransportHost()
	{

		return $this->getTransport()->getConfig( "host", "0.0.0.0" );
	}

	/**
	 * Returns the transport object used for this connection adapter.
	 *
	 * @return AbstractTransport
	 */
	public function getTransport()
	{

		return $this->transport;
	}

	/**
	 * Returns the port number of the server the underlying AbstractTransport object
	 * is connected to.
	 *
	 * @return string
	 */
	public function getTransportPort()
	{

		return $this->getTransport()->getConfig( "port", "0" );
	}

	/**
	 * Loads the transport object object used for the connection adapter and passes a given set
	 * of options.
	 *
	 * @param  array  $options
	 * @param  string $transport
	 *
	 * @throws AdapterException
	 * @return void
	 */
	protected function initTransport( $options, $transport = "Tcp" )
	{

		if ( !is_array( $options ) ) {
			throw new AdapterException( "transport parameters must provided in an array" );
		}

		$class = sprintf( '\TeamSpeak\Transport\%sTransport', ucwords( strtolower( $transport ) ) );
		if ( !class_exists( $class ) ) {
			throw new AdapterException( "Transport {$transport} does not exist." );
		}

		$this->transport = new $transport( $options );
	}
}
