<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Transport;

use TeamSpeak\Adapter\AbstractAdapter;
use TeamSpeak\Exception\TransportException;
use TeamSpeak\Model\String;
<<<<<<< HEAD
=======
use TeamSpeak\Model\Signal;
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8

/**
 * @class AbstractTransport
 * @brief Abstract class for connecting to a TeamSpeak 3 Server through different ways of transport.
 */
abstract class AbstractTransport
{

	/**
	 * Stores user-provided configuration settings.
	 *
	 * @var array
	 */
	protected $config = null;

	/**
	 * Stores the stream resource of the connection.
	 *
	 * @var resource
	 */
	protected $stream = null;

	/**
	 * Stores the AbstractAdapter object using this transport.
	 *
	 * @var AbstractAdapter
	 */
	protected $adapter = null;

	/**
	 * The AbstractTransport constructor.
	 *
	 * @param  array $config
	 *
	 * @throws TransportException
	 * @return AbstractTransport
	 */
	public function __construct( array $config )
	{

<<<<<<< HEAD
		if( !array_key_exists( "host", $config ) ) {
			throw new TransportException( "config must have a key for 'host' which specifies the server host name" );
		}

		if( !array_key_exists( "port", $config ) ) {
			throw new TransportException( "config must have a key for 'port' which specifies the server port number" );
		}

		if( !array_key_exists( "timeout", $config ) ) {
			$config[ "timeout" ] = 10;
		}

		if( !array_key_exists( "blocking", $config ) ) {
=======
		if ( !array_key_exists( "host", $config ) ) {
			throw new TransportException( "config must have a key for 'host' which specifies the server host name" );
		}

		if ( !array_key_exists( "port", $config ) ) {
			throw new TransportException( "config must have a key for 'port' which specifies the server port number" );
		}

		if ( !array_key_exists( "timeout", $config ) ) {
			$config[ "timeout" ] = 10;
		}

		if ( !array_key_exists( "blocking", $config ) ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			$config[ "blocking" ] = 1;
		}

		$this->config = $config;
	}

	/**
	 * Commit pending data.
	 *
	 * @return array
	 */
	public function __sleep()
	{

		return array( "config" );
	}

	/**
	 * Reconnects to the remote server.
	 *
	 * @return void
	 */
	public function __wakeup()
	{

		$this->connect();
	}

	/**
	 * Connects to a remote server.
	 *
	 * @throws TransportException
	 * @return void
	 */
	abstract public function connect();

	/**
	 * The AbstractTransport destructor.
	 *
	 * @return void
	 */
	public function __destruct()
	{

<<<<<<< HEAD
		if( $this->adapter instanceof AbstractAdapter ) {
=======
		if ( $this->adapter instanceof AbstractAdapter ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			$this->adapter->__destruct();
		}

		$this->disconnect();
	}

	/**
	 * Disconnects from a remote server.
	 *
	 * @return void
	 */
	abstract public function disconnect();

	/**
	 * Reads data from the stream.
	 *
	 * @param  integer $length
	 *
	 * @throws TransportException
	 * @return String
	 */
	abstract public function read( $length = 4096 );

	/**
	 * Writes data to the stream.
	 *
	 * @param  string $data
	 *
	 * @return void
	 */
	abstract public function send( $data );

	/**
	 * Returns the underlying stream resource.
	 *
	 * @return resource
	 */
	public function getStream()
	{

		return $this->stream;
	}

	/**
	 * Returns the configuration variables in this adapter.
	 *
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return array
	 */
	public function getConfig( $key = null, $default = null )
	{

<<<<<<< HEAD
		if( $key !== null ) {
=======
		if ( $key !== null ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return array_key_exists( $key, $this->config ) ? $this->config[ $key ] : $default;
		}

		return $this->config;
	}

	/**
	 * Sets the AbstractAdapter object using this transport.
	 *
	 * @param  AbstractAdapter $adapter
	 *
	 * @return void
	 */
	public function setAdapter( AbstractAdapter $adapter )
	{

		$this->adapter = $adapter;
	}

	/**
	 * Returns header/meta data from stream pointer.
	 *
	 * @throws TransportException
	 * @return array
	 */
	public function getMetaData()
	{

<<<<<<< HEAD
		if( $this->stream === null ) {
=======
		if ( $this->stream === null ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			throw new TransportException( "unable to retrieve header/meta data from stream pointer" );
		}

		return stream_get_meta_data( $this->stream );
	}

	/**
	 * Blocks a stream until data is available for reading if the stream is connected
	 * in non-blocking mode.
	 *
	 * @param  integer $time
	 *
	 * @return void
	 */
	protected function waitForReadyRead( $time = 0 )
	{

<<<<<<< HEAD
		if( !$this->isConnected() || $this->config[ "blocking" ] ) {
=======
		if ( !$this->isConnected() || $this->config[ "blocking" ] ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return;
		}

		do {
			$read = array( $this->stream );
			$null = null;

<<<<<<< HEAD
			if( $time ) {
				TeamSpeak3_Helper_Signal::getInstance()->emit(
=======
			if ( $time ) {
				Signal::getInstance()->emit(
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
					strtolower( $this->getAdapterType() ) . "WaitTimeout",
					$time,
					$this->getAdapter()
				);
			}

			$time = $time + $this->config[ "timeout" ];
<<<<<<< HEAD
		} while( @stream_select( $read, $null, $null, $this->config[ "timeout" ] ) == 0 );
=======
		} while ( @stream_select( $read, $null, $null, $this->config[ "timeout" ] ) == 0 );
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}

	/**
	 * Returns TRUE if the transport is connected.
	 *
	 * @return boolean
	 */
	public function isConnected()
	{

		return ( is_resource( $this->stream ) ) ? true : false;
	}

	/**
	 * Returns the adapter type.
	 *
	 * @return string
	 */
	public function getAdapterType()
	{

<<<<<<< HEAD
		if( $this->adapter instanceof AbstractAdapter ) {
=======
		if ( $this->adapter instanceof AbstractAdapter ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			$string = String::factory( get_class( $this->adapter ) );

			return $string->substr( $string->findLast( "_" ) )->replace( array( "_", " " ), "" )->toString();
		}

		return "Unknown";
	}

	/**
	 * Returns the AbstractAdapter object using this transport.
	 *
	 * @return AbstractAdapter
	 */
	public function getAdapter()
	{

		return $this->adapter;
	}
}
