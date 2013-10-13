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

use TeamSpeak\Model\String;
use TeamSpeak\Model\Signal;
use TeamSpeak\Helper\Profiler;
use TeamSpeak\Transport\AbstractTransport;
use TeamSpeak\Exception\Adapter\FileTransferException;

/**
 * @class FileTransferAdapter
 * @brief Provides low-level methods for file transfer communication with a TeamSpeak 3 Server.
 */
class FileTransferAdapter extends AbstractAdapter
{

	/**
	 * Connects the TeamSpeak3_Transport_Abstract object and performs initial actions on the remote
	 * server.
	 *
	 * @return void
	 */
	public function syn()
	{

		$this->initTransport( $this->options );
		$this->transport->setAdapter( $this );

		Profiler::init( spl_object_hash( $this ) );

		Signal::getInstance()->emit( "filetransferConnected", $this );
	}

	/**
	 * The FileTransferAdapter destructor.
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
	 * Sends the content of a file to the server.
	 *
	 * @param  string  $ftkey
	 * @param  integer $seek
	 * @param  string  $data
	 *
	 * @throws FileTransferException
	 * @return void
	 */
	public function upload( $ftkey, $seek, $data )
	{

		$this->init( $ftkey );

		$size = strlen( $data );
		$seek = intval( $seek );
		$pack = 4096;

		Signal::getInstance()->emit( "filetransferUploadStarted", $ftkey, $seek, $size );

		for ( ; $seek < $size; ) {
			$rest = $size - $seek;
			$pack = $rest < $pack ? $rest : $pack;
			$buff = substr( $data, $seek, $pack );
			$seek = $seek + $pack;

			$this->getTransport()->send( $buff );

			Signal::getInstance()->emit( "filetransferUploadProgress", $ftkey, $seek, $size );
		}

		$this->getProfiler()->stop();

		Signal::getInstance()->emit( "filetransferUploadFinished", $ftkey, $seek, $size );

		if ( $seek < $size ) {
			throw new FileTransferException( "incomplete file upload (" . $seek . " of " . $size . " bytes)" );
		}
	}

	/**
	 * Sends a valid file transfer key to the server to initialize the file transfer.
	 *
	 * @param  string $ftkey
	 *
	 * @throws FileTransferException
	 * @return void
	 */
	protected function init( $ftkey )
	{

		if ( strlen( $ftkey ) != 32 ) {
			throw new FileTransferException( "invalid file transfer key format" );
		}

		$this->getProfiler()->start();
		$this->getTransport()->send( $ftkey );

		Signal::getInstance()->emit( "filetransferHandshake", $this );
	}

	/**
	 * Returns the content of a downloaded file as a TeamSpeak3_Helper_String object.
	 *
	 * @param  string  $ftkey
	 * @param  integer $size
	 * @param  boolean $passthru
	 *
	 * @throws FileTransferException
	 * @return String
	 */
	public function download( $ftkey, $size, $passthru = false )
	{

		$this->init( $ftkey );

		if ( $passthru ) {
			return $this->passthru( $size );
		}

		$buff = new String( "" );
		$size = intval( $size );
		$pack = 4096;

		Signal::getInstance()->emit( "filetransferDownloadStarted", $ftkey, count( $buff ), $size );

		for ( $seek = 0; $seek < $size; ) {
			$rest = $size - $seek;
			$pack = $rest < $pack ? $rest : $pack;
			$data = $this->getTransport()->read( $rest < $pack ? $rest : $pack );
			$seek = $seek + $pack;

			$buff->append( $data );

			Signal::getInstance()->emit( "filetransferDownloadProgress", $ftkey, count( $buff ), $size );
		}

		$this->getProfiler()->stop();

		Signal::getInstance()->emit( "filetransferDownloadFinished", $ftkey, count( $buff ), $size );

		if ( strlen( $buff ) != $size ) {
			throw new FileTransferException( "incomplete file download (" . count(
					$buff
				) . " of " . $size . " bytes)" );
		}

		return $buff;
	}

	/**
	 * Outputs all remaining data on a TeamSpeak 3 file transfer stream using PHP's fpassthru()
	 * function.
	 *
	 * @param  integer $size
	 *
	 * @throws FileTransferException
	 * @return void
	 */
	protected function passthru( $size )
	{

		$buff_size = fpassthru( $this->getTransport()->getStream() );

		if ( $buff_size != $size ) {
			throw new FileTransferException( "incomplete file download (" . intval(
					$buff_size
				) . " of " . $size . " bytes)" );
		}
	}
}
