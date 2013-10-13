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

use TeamSpeak\Constant;
use TeamSpeak\Model\String;
use TeamSpeak\Exception\Adapter\ServerQueryException;

/**
 * @class ChannelGroupNode
 * @brief Class describing a TeamSpeak 3 channel group and all it's parameters.
 */
class ChannelGroupNode extends AbstractNode
{
	/**
	 * The ChannelGroupNode constructor.
	 *
	 * @param  ServerNode $server
	 * @param  array                  $info
	 * @param  string                 $index
	 *
	 * @throws ServerQueryException
	 * @return ChannelGroupNode
	 */
	public function __construct( ServerNode $server, array $info, $index = "cgid" )
	{
		$this->parent   = $server;
		$this->nodeInfo = $info;

		if ( !array_key_exists( $index, $this->nodeInfo ) ) {
			throw new ServerQueryException( "invalid groupID", 0xA00 );
		}

		$this->nodeId = $this->nodeInfo[ $index ];
	}

	/**
	 * Renames the channel group specified.
	 *
	 * @param  string $name
	 *
	 * @return void
	 */
	public function rename( $name )
	{
		return $this->getParent()->channelGroupRename( $this->getId(), $name );
	}

	/**
	 * Deletes the channel group. If $force is set to TRUE, the channel group will be
	 * deleted even if there are clients within.
	 *
	 * @param  boolean $force
	 *
	 * @return void
	 */
	public function delete( $force = false )
	{
		$this->getParent()->channelGroupDelete( $this->getId(), $force );

		unset( $this );
	}

	/**
	 * Creates a copy of the channel group and returns the new groups ID.
	 *
	 * @param  string  $name
	 * @param  integer $tcgid
	 * @param  integer $type
	 *
	 * @return integer
	 */
	public function copy( $name = null, $tcgid = 0, $type = Constant::GROUP_DBTYPE_REGULAR )
	{
		return $this->getParent()->channelGroupCopy( $this->getId(), $name, $tcgid, $type );
	}

	/**
	 * Returns a list of permissions assigned to the channel group.
	 *
	 * @param  boolean $permsid
	 *
	 * @return array
	 */
	public function permList( $permsid = false )
	{
		return $this->getParent()->channelGroupPermList( $this->getId(), $permsid );
	}

	/**
	 * Adds a set of specified permissions to the channel group. Multiple permissions
	 * can be added by providing the two parameters of each permission in separate arrays.
	 *
	 * @param  integer $permid
	 * @param  integer $permvalue
	 *
	 * @return void
	 */
	public function permAssign( $permid, $permvalue )
	{
		return $this->getParent()->channelGroupPermAssign( $this->getId(), $permid, $permvalue );
	}

	/**
	 * Alias for permAssign().
	 *
	 * @deprecated
	 */
	public function permAssignByName( $permname, $permvalue )
	{
		return $this->permAssign( $permname, $permvalue );
	}

	/**
	 * Removes a set of specified permissions from the channel group. Multiple
	 * permissions can be removed at once.
	 *
	 * @param  integer $permid
	 *
	 * @return void
	 */
	public function permRemove( $permid )
	{
		return $this->getParent()->channelGroupPermRemove( $this->getId(), $permid );
	}

	/**
	 * Alias for permAssign().
	 *
	 * @deprecated
	 */
	public function permRemoveByName( $permname )
	{
		return $this->permRemove( $permname );
	}

	/**
	 * Returns a list of clients assigned to the server group specified.
	 *
	 * @return array
	 */
	public function clientList()
	{
		return $this->getParent()->channelGroupClientList( $this->getId() );
	}

	/**
	 * Alias for privilegeKeyCreate().
	 *
	 * @deprecated
	 */
	public function tokenCreate( $cid, $description = null, $customset = null )
	{
		return $this->privilegeKeyCreate( $cid, $description, $customset );
	}

	/**
	 * Creates a new privilege key (token) for the channel group and returns the key.
	 *
	 * @param  integer $cid
	 * @param  string  $description
	 * @param  string  $customset
	 *
	 * @return String
	 */
	public function privilegeKeyCreate( $cid, $description = null, $customset = null )
	{
		return $this->getParent()->privilegeKeyCreate(
			Constant::TOKEN_CHANNELGROUP,
			$this->getId(),
			$cid,
			$description,
			$customset
		);
	}

	/**
	 * Sends a text message to all clients residing in the channel group on the virtual server.
	 *
	 * @param  string $msg
	 *
	 * @throws ServerQueryException
	 * @return void
	 */
	public function message( $msg )
	{
		foreach ( $this as $client ) {
			try {
				$this->execute(
					"sendtextmessage",
					array( "msg" => $msg, "target" => $client, "targetmode" => Constant::TEXTMSG_CLIENT )
				);
			} catch ( ServerQueryException $e ) {
				/* ERROR_client_invalid_id */
				if ( $e->getCode() != 0x0200 ) {
					throw $e;
				}
			}
		}
	}

	/**
	 * Downloads and returns the channel groups icon file content.
	 *
	 * @return String
	 */
	public function iconDownload()
	{
		if ( $this->iconIsLocal( "iconid" ) || $this[ "iconid" ] == 0 ) {
			return;
		}

		$download = $this->getParent()->transferInitDownload(
			rand( 0x0000, 0xFFFF ),
			0,
			$this->iconGetName( "iconid" )
		);
		$transfer = Constant::factory( "filetransfer://" . $download[ "host" ] . ":" . $download[ "port" ] );

		return $transfer->download( $download[ "ftkey" ], $download[ "size" ] );
	}

	/**
	 * @ignore
	 */
	protected function fetchNodeList()
	{
		$this->nodeList = array();

		foreach ( $this->getParent()->clientList() as $client ) {
			if ( $client[ "client_channel_group_id" ] == $this->getId() ) {
				$this->nodeList[ ] = $client;
			}
		}
	}

	/**
	 * Returns a unique identifier for the node which can be used as a HTML property.
	 *
	 * @return string
	 */
	public function getUniqueId()
	{
		return $this->getParent()->getUniqueId() . "_cg" . $this->getId();
	}

	/**
	 * Returns the name of a possible icon to display the node object.
	 *
	 * @return string
	 */
	public function getIcon()
	{
		return "group_channel";
	}

	/**
	 * Returns a symbol representing the node.
	 *
	 * @return string
	 */
	public function getSymbol()
	{
		return "%";
	}

	/**
	 * Returns a string representation of this node.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this[ "name" ];
	}
}

