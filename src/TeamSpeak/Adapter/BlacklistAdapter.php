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

use TeamSpeak\Exception\Adapter\BlacklistException;
use TeamSpeak\Helper\Profiler;
use TeamSpeak\Model\Signal;
use TeamSpeak\Transport\AbstractTransport;

/**
 * Provides methods to check if an IP address is currently blacklisted.
 */
class BlacklistAdapter extends AbstractAdapter
{

	/**
	 * The IPv4 address or FQDN of the TeamSpeak Systems update server.
	 *
	 * @var string
	 */
	protected $default_host = "blacklist.teamspeak.com";

	/**
	 * The UDP port number of the TeamSpeak Systems update server.
	 *
	 * @var integer
	 */
	protected $default_port = 17385;

	/**
	 * Stores an array containing the latest build numbers.
	 *
	 * @var array
	 */
	protected $build_numbers = null;

	/**
	 * Connects the AbstractTransport object and performs initial actions on the remote
	 * server.
	 *
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

		$this->initTransport( $this->options, "Udp" );
		$this->transport->setAdapter( $this );

		Profiler::init( spl_object_hash( $this ) );

		Signal::getInstance()->emit( "blacklistConnected", $this );
	}

	/**
	 * The BlacklistAdapter destructor.
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
	 * Returns TRUE if a specified $host IP address is currently blacklisted.
	 *
	 * @param  string $host
	 *
	 * @throws BlacklistException
	 * @return boolean
	 */
	public function isBlacklisted( $host )
	{

		if ( ip2long( $host ) === false ) {
			$addr = gethostbyname( $host );

			if ( $addr == $host ) {
				throw new BlacklistException( "unable to resolve IPv4 address (" . $host . ")" );
			}

			$host = $addr;
		}

		$this->getTransport()->send( "ip4:" . $host );
		$repl = $this->getTransport()->read( 1 );
		$this->getTransport()->disconnect();

		if ( !count( $repl ) ) {
			return false;
		}

		return ( $repl->toInt() ) ? false : true;
	}
}
