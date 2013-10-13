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

use TeamSpeak\Exception\Adapter\UpdateException;
use TeamSpeak\Helper\Profiler;
use TeamSpeak\Model\Signal;

use TeamSpeak\Model\String;
use TeamSpeak\Transport\AbstractTransport;

/**
 * Provides methods to query the latest TeamSpeak 3 build numbers from the master server.
 */
class UpdateAdapter extends AbstractAdapter
{

	/**
	 * The IPv4 address or FQDN of the TeamSpeak Systems update server.
	 *
	 * @var string
	 */
	protected $default_host = "update.teamspeak.com";

	/**
	 * The UDP port number of the TeamSpeak Systems update server.
	 *
	 * @var integer
	 */
	protected $default_port = 17384;

	/**
	 * Stores an array containing the latest build numbers (integer timestamps).
	 *
	 * @var array
	 */
	protected $build_datetimes = null;

	/**
	 * Stores an array containing the latest version strings.
	 *
	 * @var array
	 */
	protected $version_strings = null;

	/**
	 * Connects the TeamSpeak3_Transport_Abstract object and performs initial actions on the remote
	 * server.
	 *
	 * @throws UpdateException
	 * @return void
	 */
	public function syn()
	{

		if ( !isset( $this->options[ "host" ] ) || empty( $this->options[ "host" ] ) ) {
			$this->options[ "host" ] = $this->default_host;
		}
		if ( !isset( $this->options[ "port" ] ) || empty( $this->options[ "port" ] ) ) {
			$this->options[ "port" ] = $this->default_port;
		}

		$this->initTransport( $this->options, "TeamSpeak3_Transport_UDP" );
		$this->transport->setAdapter( $this );

		Profiler::init( spl_object_hash( $this ) );

		$this->getTransport()->send( String::fromHex( 33 ) );

		if ( !preg_match_all(
				"/,?(\d+)#([0-9a-zA-Z\._-]+),?/",
				$this->getTransport()->read( 96 ),
				$matches
			) || !isset( $matches[ 1 ] ) || !isset( $matches[ 2 ] )
		) {
			throw new UpdateException( "invalid reply from the server" );
		}

		$this->build_datetimes = $matches[ 1 ];
		$this->version_strings = $matches[ 2 ];

		Signal::getInstance()->emit( "updateConnected", $this );
	}

	/**
	 * The UpdateAdapter destructor.
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
	 * Alias for getRev() using the 'stable' update channel.
	 *
	 * @return integer
	 */
	public function getClientRev()
	{

		return $this->getRev( "stable" );
	}

	/**
	 * Returns the current build number for a specified update channel. Note that since version
	 * 3.0.0, the build number represents an integer timestamp. $channel must be set to one of
	 * the following values:
	 *
	 * - stable
	 * - beta
	 * - alpha
	 * - server
	 *
	 * @param  string $channel
	 *
	 * @throws UpdateException
	 * @return integer
	 */
	public function getRev( $channel = "stable" )
	{

		switch ( $channel ) {
			case "stable":
				return isset( $this->build_datetimes[ 0 ] ) ? $this->build_datetimes[ 0 ] : null;

			case "beta":
				return isset( $this->build_datetimes[ 1 ] ) ? $this->build_datetimes[ 1 ] : null;

			case "alpha":
				return isset( $this->build_datetimes[ 2 ] ) ? $this->build_datetimes[ 2 ] : null;

			case "server":
				return isset( $this->build_datetimes[ 3 ] ) ? $this->build_datetimes[ 3 ] : null;

			default:
				throw new UpdateException( "invalid update channel identifier (" . $channel . ")" );
		}
	}

	/**
	 * Alias for getRev() using the 'server' update channel.
	 *
	 * @return integer
	 */
	public function getServerRev()
	{

		return $this->getRev( "server" );
	}

	/**
	 * Alias for getVersion() using the 'stable' update channel.
	 *
	 * @return integer
	 */
	public function getClientVersion()
	{

		return $this->getVersion( "stable" );
	}

	/**
	 * Returns the current version string for a specified update channel. $channel must be set to
	 * one of the following values:
	 *
	 * - stable
	 * - beta
	 * - alpha
	 * - server
	 *
	 * @param  string $channel
	 *
	 * @throws UpdateException
	 * @return integer
	 */
	public function getVersion( $channel = "stable" )
	{

		switch ( $channel ) {
			case "stable":
				return isset( $this->version_strings[ 0 ] ) ? $this->version_strings[ 0 ] : null;

			case "beta":
				return isset( $this->version_strings[ 1 ] ) ? $this->version_strings[ 1 ] : null;

			case "alpha":
				return isset( $this->version_strings[ 2 ] ) ? $this->version_strings[ 2 ] : null;

			case "server":
				return isset( $this->version_strings[ 3 ] ) ? $this->version_strings[ 3 ] : null;

			default:
				throw new UpdateException( "invalid update channel identifier (" . $channel . ")" );
		}
	}

	/**
	 * Alias for getVersion() using the 'server' update channel.
	 *
	 * @return integer
	 */
	public function getServerVersion()
	{

		return $this->getVersion( "server" );
	}
}
