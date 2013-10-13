<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak;

use TeamSpeak\Adapter\ServerQueryAdapter;
use TeamSpeak\Helper\Profiler;
use TeamSpeak\Node\AbstractNode;
use TeamSpeak\Node\ServerNode;
use TeamSpeak\Adapter\AbstractAdapter;

use TeamSpeak\Exception\AdapterException;

/**
 * Factory class all for TeamSpeak 3 PHP Framework objects.
 */
class Factory
{
	/**
	 * Factory for AbstractAdapter classes. $uri must be formatted as
	 * "<adapter>://<user>:<pass>@<host>:<port>/<options>#<flags>". All parameters
	 * except adapter, host and port are optional.
	 *
	 * === Supported Options ===
	 *   - timeout
	 *   - blocking
	 *   - nickname
	 *   - no_query_clients
	 *   - use_offline_as_virtual
	 *   - clients_before_channels
	 *   - server_id|server_uid|server_port|server_name|server_tsdns
	 *   - channel_id|channel_name
	 *   - client_id|client_uid|client_name
	 *
	 * === Supported Flags (only one per $uri) ===
	 *   - no_query_clients
	 *   - use_offline_as_virtual
	 *   - clients_before_channels
	 *
	 * === URI Examples ===
	 *   - serverquery://127.0.0.1:10011/
	 *   - serverquery://127.0.0.1:10011/?server_port=9987&channel_id=1
	 *   - serverquery://127.0.0.1:10011/?server_port=9987&channel_id=1#no_query_clients
	 *   - serverquery://127.0.0.1:10011/?server_port=9987&client_name=ScP
	 *   - filetransfer://127.0.0.1:30011/
	 *   - blacklist
	 *   - update
	 *
	 * @param  string $uri
	 *
	 * @throws Exception\AdapterException
	 * @return AbstractAdapter
	 * @return \TeamSpeak\Adapter\AbstractAdapter
	 */
	public static function create( $uri )
	{

		self::init();

		$uri = new Model\Uri( $uri );

		$adapter = self::getAdapterName( $uri->getScheme() );
		$options = array(
			"host"     => $uri->getHost(),
			"port"     => $uri->getPort(),
			"timeout"  => intval( $uri->getQueryVar( "timeout", 10 ) ),
			"blocking" => intval( $uri->getQueryVar( "blocking", 1 ) )
		);

		$className = sprintf( '\TeamSpeak\Adapter\%sAdapter', ucwords( strtolower( $adapter ) ) );
		if( !class_exists( $className ) ) {
			throw new AdapterException( "Adapter {$className} does not exist." );
		}

		$adapter = new $className( $options );

		if ( $adapter instanceof ServerQueryAdapter ) {
			$node = $adapter->getHost();

			if ( $uri->hasUser() && $uri->hasPass() ) {
				$node->login( $uri->getUser(), $uri->getPass() );
			}

			/* option to pre-define nickname */
			if ( $uri->hasQueryVar( "nickname" ) ) {
				$node->setPredefinedQueryName( $uri->getQueryVar( "nickname" ) );
			}

			/* flag to use offline servers in virtual mode */
			if ( $uri->getFragment() == "use_offline_as_virtual" ) {
				$node->setUseOfflineAsVirtual( true );
			} elseif ( $uri->hasQueryVar( "use_offline_as_virtual" ) ) {
				$node->setUseOfflineAsVirtual( $uri->getQueryVar( "use_offline_as_virtual" ) ? true : false );
			}

			/* flag to fetch clients before sub-channels */
			if ( $uri->getFragment() == "clients_before_channels" ) {
				$node->setLoadClientlistFirst( true );
			} elseif ( $uri->hasQueryVar( "clients_before_channels" ) ) {
				$node->setLoadClientlistFirst( $uri->getQueryVar( "clients_before_channels" ) ? true : false );
			}

			/* flag to hide ServerQuery clients */
			if ( $uri->getFragment() == "no_query_clients" ) {
				$node->setExcludeQueryClients( true );
			} elseif ( $uri->hasQueryVar( "no_query_clients" ) ) {
				$node->setExcludeQueryClients( $uri->getQueryVar( "no_query_clients" ) ? true : false );
			}

			/* access server node object */
			if ( $uri->hasQueryVar( "server_id" ) ) {
				$node = $node->serverGetById( $uri->getQueryVar( "server_id" ) );
			} elseif ( $uri->hasQueryVar( "server_uid" ) ) {
				$node = $node->serverGetByUid( $uri->getQueryVar( "server_uid" ) );
			} elseif ( $uri->hasQueryVar( "server_port" ) ) {
				$node = $node->serverGetByPort( $uri->getQueryVar( "server_port" ) );
			} elseif ( $uri->hasQueryVar( "server_name" ) ) {
				$node = $node->serverGetByName( $uri->getQueryVar( "server_name" ) );
			} elseif ( $uri->hasQueryVar( "server_tsdns" ) ) {
				$node = $node->serverGetByTSDNS( $uri->getQueryVar( "server_tsdns" ) );
			}

			/* direct access to node objects */
			if ( $node instanceof ServerNode ) {
				/* access channel node object */
				if ( $uri->hasQueryVar( "channel_id" ) ) {
					$node = $node->channelGetById( $uri->getQueryVar( "channel_id" ) );
				} elseif ( $uri->hasQueryVar( "channel_name" ) ) {
					$node = $node->channelGetByName( $uri->getQueryVar( "channel_name" ) );
				}

				/* access client node object */
				if ( $uri->hasQueryVar( "client_id" ) ) {
					$node = $node->clientGetById( $uri->getQueryVar( "client_id" ) );
				}
				if ( $uri->hasQueryVar( "client_uid" ) ) {
					$node = $node->clientGetByUid( $uri->getQueryVar( "client_uid" ) );
				} elseif ( $uri->hasQueryVar( "client_name" ) ) {
					$node = $node->clientGetByName( $uri->getQueryVar( "client_name" ) );
				}
			}

			return $node;
		}

		return $adapter;
	}

	/**
	 * Checks for required PHP features, enables autoloading and starts a default profiler.
	 *
	 * @throws \LogicException
	 * @return void
	 */
	public static function init()
	{

		if ( version_compare( phpversion(), "5.3.1" ) == -1 ) {
			throw new \LogicException( "this particular software cannot be used with the installed version of PHP" );
		}

		if ( !function_exists( "stream_socket_client" ) ) {
			throw new \LogicException( "network functions are not available in this PHP installation" );
		}

		Profiler::start();
	}
}