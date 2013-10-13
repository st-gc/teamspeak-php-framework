<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Node;

use TeamSpeak\Adapter\ServerQuery\Reply;
use TeamSpeak\Model\String;
use TeamSpeak\Helper\Convert;
use TeamSpeak\Adapter\ServerQueryAdapter;
use TeamSpeak\Exception\Adapter\ServerQueryException;
use TeamSpeak\Exception\NodeException;

use TeamSpeak\Node\ClientNode;
use TeamSpeak\Node\ChannelNode;

/**
 * @class AbstractNode
 * @brief Abstract class describing a TeamSpeak 3 node and all it's parameters.
 */
abstract class AbstractNode implements \RecursiveIterator, \ArrayAccess, \Countable
{
	/**
	 * @ignore
	 */
	protected $parent = null;

	/**
	 * @ignore
	 */
	protected $server = null;

	/**
	 * @ignore
	 */
	protected $nodeId = 0x00;

	/**
	 * @ignore
	 */
	protected $nodeList = null;

	/**
	 * @ignore
	 */
	protected $nodeInfo = array();

	/**
	 * @ignore
	 */
	protected $storage = array();

	/**
	 * Sends a prepared command to the server and returns the result.
	 *
	 * @param  string  $cmd
	 * @param  boolean $throw
	 *
	 * @return Reply
	 */
	public function request( $cmd, $throw = true )
	{
		return $this->getParent()->request( $cmd, $throw );
	}

	/**
	 * Uses given parameters and returns a prepared ServerQuery command.
	 *
	 * @param  string $cmd
	 * @param  array  $params
	 *
	 * @return String
	 */
	public function prepare( $cmd, array $params = array() )
	{
		return $this->getParent()->prepare( $cmd, $params );
	}

	/**
	 * Prepares and executes a ServerQuery command and returns the result.
	 *
	 * @param  string $cmd
	 * @param  array  $params
	 *
	 * @return Reply
	 */
	public function execute( $cmd, array $params = array() )
	{
		return $this->request( $this->prepare( $cmd, $params ) );
	}

	/**
	 * Returns the parent object of the current node.
	 *
	 * @return ServerQueryAdapter
	 * @return AbstractNode
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Returns the primary ID of the current node.
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->nodeId;
	}

	/**
	 * Returns TRUE if the node icon has a local source.
	 *
	 * @param  string $key
	 *
	 * @return boolean
	 */
	public function iconIsLocal( $key )
	{
		return ( $this[ $key ] > 0 && $this[ $key ] < 1000 ) ? true : false;
	}

	/**
	 * Returns the internal path of the node icon.
	 *
	 * @param  string $key
	 *
	 * @return String
	 */
	public function iconGetName( $key )
	{
		$iconid = ( $this[ $key ] < 0 ) ? ( pow( 2, 32 ) ) - ( $this[ $key ] * -1 ) : $this[ $key ];

		return new String( "/icon_" . $iconid );
	}

	/**
	 * Returns a possible classname for the node which can be used as a HTML property.
	 *
	 * @param  string $prefix
	 *
	 * @return string
	 */
	public function getClass( $prefix = "ts3_" )
	{
		if ( $this instanceof Channel && $this->isSpacer() ) {
			return $prefix . "spacer";
		} elseif ( $this instanceof Client && $this[ "client_type" ] ) {
			return $prefix . "query";
		}

		return $prefix . String::factory( get_class( $this ) )->section( "_", 2 )->toLower();
	}

	/**
	 * Returns a unique identifier for the node which can be used as a HTML property.
	 *
	 * @return string
	 */
	abstract public function getUniqueId();

	/**
	 * Returns the name of a possible icon to display the node object.
	 *
	 * @return string
	 */
	abstract public function getIcon();

	/**
	 * Returns a symbol representing the node.
	 *
	 * @return string
	 */
	abstract public function getSymbol();

	/**
	 * Returns the HTML code to display a TeamSpeak 3 viewer.
	 *
	 * @param  ViewerInterface $viewer
	 *
	 * @return string
	 */
	public function getViewer( ViewerInterface $viewer )
	{
		$html = $viewer->fetchObject( $this );

		$iterator = new \RecursiveIteratorIterator( $this, \RecursiveIteratorIterator::SELF_FIRST );

		foreach ( $iterator as $node ) {
			$siblings = array();

			for ( $level = 0; $level < $iterator->getDepth(); $level++ ) {
				$siblings[ ] = ( $iterator->getSubIterator( $level )->hasNext() ) ? 1 : 0;
			}

			$siblings[ ] = ( !$iterator->getSubIterator( $level )->hasNext() ) ? 1 : 0;

			$html .= $viewer->fetchObject( $node, $siblings );
		}

		return $html;
	}

	/**
	 * Filters given node list array using specified filter rules.
	 *
	 * @param  array $nodes
	 * @param  array $rules
	 *
	 * @return array
	 */
	protected function filterList( array $nodes = array(), array $rules = array() )
	{
		if ( !empty( $rules ) ) {
			foreach ( $nodes as $node ) {
				if ( !$node instanceof AbstractNode ) {
					continue;
				}

				$props = $node->getInfo( false );
				$props = array_intersect_key( $props, $rules );
				$match = true;

				foreach ( $props as $key => $val ) {
					if ( $val instanceof String ) {
						$match = $val->contains( $rules[ $key ], true );
					} else {
						$match = $val == $rules[ $key ];
					}

					if ( $match === false ) {
						unset( $nodes[ $node->getId() ] );
					}
				}
			}
		}

		return $nodes;
	}

	/**
	 * Returns all information available on this node. If $convert is enabled, some property
	 * values will be converted to human-readable values.
	 *
	 * @param  boolean $extend
	 * @param  boolean $convert
	 *
	 * @return array
	 */
	public function getInfo( $extend = true, $convert = false )
	{
		if ( $extend ) {
			$this->fetchNodeInfo();
		}

		if ( $convert ) {
			$info = $this->nodeInfo;

			foreach ( $info as $key => $val ) {
				$key = String::factory( $key );

				if ( $key->contains( "_bytes_" ) ) {
					$info[ $key->toString() ] = Convert::bytes( $val );
				} elseif ( $key->contains( "_bandwidth_" ) ) {
					$info[ $key->toString() ] = Convert::bytes( $val ) . "/s";
				} elseif ( $key->contains( "_packets_" ) ) {
					$info[ $key->toString() ] = number_format( $val, null, null, "." );
				} elseif ( $key->contains( "_packetloss_" ) ) {
					$info[ $key->toString() ] = sprintf( "%01.2f", floatval( $val->toString() ) * 100 ) . "%";
				} elseif ( $key->endsWith( "_uptime" ) ) {
					$info[ $key->toString() ] = Convert::seconds( $val );
				} elseif ( $key->endsWith( "_version" ) ) {
					$info[ $key->toString() ] = Convert::version( $val );
				} elseif ( $key->endsWith( "_icon_id" ) ) {
					$info[ $key->toString() ] = $this->iconGetName( $key )->filterDigits();
				}
			}

			return $info;
		}

		return $this->nodeInfo;
	}

	/**
	 * Returns the specified property or a pre-defined default value from the node info array.
	 *
	 * @param  string $property
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	public function getProperty( $property, $default = null )
	{
		if ( !$this->offsetExists( $property ) ) {
			$this->fetchNodeInfo();
		}

		if ( !$this->offsetExists( $property ) ) {
			return $default;
		}

		return $this->nodeInfo[ (string)$property ];
	}

	/**
	 * Returns a string representation of this node.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return get_class( $this );
	}

	/**
	 * Returns a string representation of this node.
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->__toString();
	}

	/**
	 * Returns an assoc array filled with current node info properties.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->nodeList;
	}

	/**
	 * Called whenever we're using an unknown method.
	 *
	 * @param  string $name
	 * @param  array  $args
	 *
	 * @throws NodeException
	 * @return mixed
	 */
	public function __call( $name, array $args )
	{
		if ( $this->getParent() instanceof AbstractNode ) {
			return call_user_func_array( array( $this->getParent(), $name ), $args );
		}

		throw new NodeException( "node method '" . $name . "()' does not exist" );
	}

	/**
	 * Writes data to the internal storage array.
	 *
	 * @param  string $key
	 * @param  mixed  $val
	 *
	 * @return void
	 */
	protected function setStorage( $key, $val )
	{
		$this->storage[ $key ] = $val;
	}

	/**
	 * Returns data from the internal storage array.
	 *
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	protected function getStorage( $key, $default = null )
	{
		return ( array_key_exists(
				$key,
				$this->storage
			) && !empty( $this->storage[ $key ] ) ) ? $this->storage[ $key ] : $default;
	}

	/**
	 * Deletes data from the internal storage array.
	 *
	 * @param  string $key
	 *
	 * @return void
	 */
	protected function delStorage( $key )
	{
		unset( $this->storage[ $key ] );
	}

	/**
	 * Commit pending data.
	 *
	 * @return array
	 */
	public function __sleep()
	{
		return array( "parent", "storage", "nodeId" );
	}

	/**
	 * @ignore
	 */
	protected function fetchNodeList()
	{
		$this->nodeList = array();
	}

	/**
	 * @ignore
	 */
	protected function fetchNodeInfo()
	{
		return;
	}

	/**
	 * @ignore
	 */
	protected function resetNodeInfo()
	{
		$this->nodeInfo = array();
	}

	/**
	 * @ignore
	 */
	protected function verifyNodeList()
	{
		if ( $this->nodeList === null ) {
			$this->fetchNodeList();
		}
	}

	/**
	 * @ignore
	 */
	protected function resetNodeList()
	{
		$this->nodeList = null;
	}

	/**
	 * @ignore
	 */
	public function count()
	{
		$this->verifyNodeList();

		return count( $this->nodeList );
	}

	/**
	 * @ignore
	 */
	public function current()
	{
		$this->verifyNodeList();

		return current( $this->nodeList );
	}

	/**
	 * @ignore
	 */
	public function getChildren()
	{
		$this->verifyNodeList();

		return $this->current();
	}

	/**
	 * @ignore
	 */
	public function hasChildren()
	{
		$this->verifyNodeList();

		return $this->current()->count() > 0;
	}

	/**
	 * @ignore
	 */
	public function hasNext()
	{
		$this->verifyNodeList();

		return $this->key() + 1 < $this->count();
	}

	/**
	 * @ignore
	 */
	public function key()
	{
		$this->verifyNodeList();

		return key( $this->nodeList );
	}

	/**
	 * @ignore
	 */
	public function valid()
	{
		$this->verifyNodeList();

		return $this->key() !== null;
	}

	/**
	 * @ignore
	 */
	public function next()
	{
		$this->verifyNodeList();

		return next( $this->nodeList );
	}

	/**
	 * @ignore
	 */
	public function rewind()
	{
		$this->verifyNodeList();

		return reset( $this->nodeList );
	}

	/**
	 * @ignore
	 */
	public function offsetExists( $offset )
	{
		return array_key_exists( (string)$offset, $this->nodeInfo ) ? true : false;
	}

	/**
	 * @ignore
	 */
	public function offsetGet( $offset )
	{
		if ( !$this->offsetExists( $offset ) ) {
			$this->fetchNodeInfo();
		}

		if ( !$this->offsetExists( $offset ) ) {
			throw new ServerQueryException( "invalid parameter", 0x602 );
		}

		return $this->nodeInfo[ (string)$offset ];
	}

	/**
	 * @ignore
	 */
	public function offsetSet( $offset, $value )
	{
		if ( method_exists( $this, "modify" ) ) {
			return $this->modify( array( (string)$offset => $value ) );
		}

		throw new NodeException( "node '" . get_class( $this ) . "' is read only" );
	}

	/**
	 * @ignore
	 */
	public function offsetUnset( $offset )
	{
		unset( $this->nodeInfo[ (string)$offset ] );
	}

	/**
	 * @ignore
	 */
	public function __get( $offset )
	{
		return $this->offsetGet( $offset );
	}

	/**
	 * @ignore
	 */
	public function __set( $offset, $value )
	{
		$this->offsetSet( $offset, $value );
	}
}
