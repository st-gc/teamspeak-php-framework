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
<<<<<<< HEAD
=======
use TeamSpeak\Model\Signal;
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
use TeamSpeak\Transport\AbstractTransport;

/**
 * Class for connecting to a remote server through TCP.
 */
class TcpTransport extends AbstractTransport
{

	/**
	 * Disconnects from a remote server.
	 *
	 * @return void
	 */
	public function disconnect()
	{

<<<<<<< HEAD
		if( $this->stream === null ) {
=======
		if ( $this->stream === null ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return;
		}

		$this->stream = null;

<<<<<<< HEAD
		TeamSpeak3_Helper_Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "Disconnected" );
=======
		Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "Disconnected" );
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
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

		$data = @stream_get_contents( $this->stream, $length );

<<<<<<< HEAD
		TeamSpeak3_Helper_Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "DataRead", $data );

		if( $data === false ) {
=======
		Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "DataRead", $data );

		if ( $data === false ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
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

<<<<<<< HEAD
		if( $this->stream !== null ) return;
=======
		if ( $this->stream !== null ) {
			return;
		}
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8

		$host = strval( $this->config[ "host" ] );
		$port = strval( $this->config[ "port" ] );

		$address = "tcp://" . $host . ":" . $port;
		$timeout = intval( $this->config[ "timeout" ] );

		$this->stream = @stream_socket_client( $address, $errno, $errstr, $timeout );

<<<<<<< HEAD
		if( $this->stream === false ) {
=======
		if ( $this->stream === false ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			throw new TransportException( String::factory( $errstr )->toUtf8()->toString(), $errno );
		}

		@stream_set_timeout( $this->stream, $timeout );
		@stream_set_blocking( $this->stream, $this->config[ "blocking" ] ? 1 : 0 );
	}

	/**
	 * Reads a single line of data from the stream.
	 *
	 * @param  string $token
	 *
	 * @throws TransportException
	 * @return String
	 */
	public function readLine( $token = "\n" )
	{

		$this->connect();

		$line = String::factory( "" );

<<<<<<< HEAD
		while( !$line->endsWith( $token ) ) {
=======
		while ( !$line->endsWith( $token ) ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			$this->waitForReadyRead();

			$data = @fgets( $this->stream, 4096 );

<<<<<<< HEAD
			TeamSpeak3_Helper_Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "DataRead", $data );

			if( $data === false ) {
				if( $line->count() ) {
=======
			Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "DataRead", $data );

			if ( $data === false ) {
				if ( $line->count() ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
					$line->append( $token );
				} else {
					throw new TransportException( "connection to server '" . $this->config[ "host" ] . ":" . $this->config[ "port" ] . "' lost" );
				}
			} else {
				$line->append( $data );
			}
		}

		return $line->trim();
	}

	/**
	 * Writes a line of data to the stream.
	 *
	 * @param  string $data
	 * @param  string $separator
	 *
	 * @return void
	 */
	public function sendLine( $data, $separator = "\n" )
	{

		$size = strlen( $data );
		$pack = 4096;

<<<<<<< HEAD
		for( $seek = 0; $seek < $size; ) {
=======
		for ( $seek = 0; $seek < $size; ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			$rest = $size - $seek;
			$pack = $rest < $pack ? $rest : $pack;
			$buff = substr( $data, $seek, $pack );
			$seek = $seek + $pack;

<<<<<<< HEAD
			if( $seek >= $size ) $buff .= $separator;
=======
			if ( $seek >= $size ) {
				$buff .= $separator;
			}
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8

			$this->send( $buff );
		}
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

<<<<<<< HEAD
		TeamSpeak3_Helper_Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "DataSend", $data );
=======
		Signal::getInstance()->emit( strtolower( $this->getAdapterType() ) . "DataSend", $data );
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}
}
