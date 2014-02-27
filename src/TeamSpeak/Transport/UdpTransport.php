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

use TeamSpeak\Exception\TransportException;
use TeamSpeak\Model\String;
use TeamSpeak\Transport\AbstractTransport;

/**
 * @class UdpTransport
 * @brief Class for connecting to a remote server through UDP.
 */
class UdpTransport extends AbstractTransport
{

	/**
	 * Disconnects from a remote server.
	 *
	 * @return void
	 */
	public function disconnect()
	{

		if( $this->stream === null ) {
			return;
		}

		$this->stream = null;

		TeamSpeak3_Helper_Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "Disconnected" );
	}

	/**
	 * Reads data from the stream.
	 *
	 * @param  integer $length
	 *
	 * @throws TransportException
	 * @return String
	 */
	public function read( $length = 4096 )
	{

		$this->connect();
		$this->waitForReadyRead();

		$data = @fread( $this->stream, $length );

		TeamSpeak3_Helper_Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "DataRead", $data );

		if( $data === false ) {
			throw new TransportException( "connection to server '" . $this->config[ "host" ] . ":" . $this->config[ "port" ] . "' lost" );
		}

		return new String( $data );
	}

	/**
	 * Connects to a remote server.
	 *
	 * @throws TransportException
	 * @return void
	 */
	public function connect()
	{

		if( $this->stream !== null ) return;

		$host = strval( $this->config[ "host" ] );
		$port = strval( $this->config[ "port" ] );

		$address = "udp://" . $host . ":" . $port;
		$timeout = intval( $this->config[ "timeout" ] );

		$this->stream = @stream_socket_client( $address, $errno, $errstr, $timeout );

		if( $this->stream === false ) {
			throw new TransportException( String::factory( $errstr )->toUtf8()->toString(), $errno );
		}

		@stream_set_timeout( $this->stream, $timeout );
		@stream_set_blocking( $this->stream, $this->config[ "blocking" ] ? 1 : 0 );
	}

	/**
	 * Writes data to the stream.
	 *
	 * @param  string $data
	 *
	 * @return void
	 */
	public function send( $data )
	{

		$this->connect();

		@stream_socket_sendto( $this->stream, $data );

		TeamSpeak3_Helper_Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "DataSend", $data );
	}
}
