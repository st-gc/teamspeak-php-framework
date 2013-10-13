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
 * @class ClientNode
 * @brief Class describing a TeamSpeak 3 client and all it's parameters.
 */
class ClientNode extends AbstractNode
{
	/**
	 * The ClientNode constructor.
	 *
	 * @param  ServerNode $server
	 * @param  array                  $info
	 * @param  string                 $index
	 *
	 * @throws ServerQueryException
	 * @return ClientNode
	 */
	public function __construct( ServerNode $server, array $info, $index = "clid" )
	{
		$this->parent   = $server;
		$this->nodeInfo = $info;

		if ( !array_key_exists( $index, $this->nodeInfo ) ) {
			throw new ServerQueryException( "invalid clientID", 0x200 );
		}

		$this->nodeId = $this->nodeInfo[ $index ];
	}

	/**
	 * Changes the clients properties using given properties.
	 *
	 * @param  array $properties
	 *
	 * @return void
	 */
	public function modify( array $properties )
	{
		$properties[ "clid" ] = $this->getId();

		$this->execute( "clientedit", $properties );
		$this->resetNodeInfo();
	}

	/**
	 * Changes the clients properties using given properties.
	 *
	 * @param  array $properties
	 *
	 * @return void
	 */
	public function modifyDb( array $properties )
	{
		return $this->getParent()->clientModifyDb( $this[ "client_database_id" ], $properties );
	}

	/**
	 * Deletes the clients properties from the database.
	 *
	 * @return void
	 */
	public function deleteDb()
	{
		return $this->getParent()->clientDeleteDb( $this[ "client_database_id" ] );
	}

	/**
	 * Returns a list of properties from the database for the client.
	 *
	 * @return array
	 */
	public function infoDb()
	{
		return $this->getParent()->clientInfoDb( $this[ "client_database_id" ] );
	}

	/**
	 * Sends a text message to the client.
	 *
	 * @param  string $msg
	 *
	 * @return void
	 */
	public function message( $msg )
	{
		$this->execute(
			"sendtextmessage",
			array( "msg" => $msg, "target" => $this->getId(), "targetmode" => Constant::TEXTMSG_CLIENT )
		);
	}

	/**
	 * Moves the client to another channel.
	 *
	 * @param  integer $cid
	 * @param  string  $cpw
	 *
	 * @return void
	 */
	public function move( $cid, $cpw = null )
	{
		return $this->getParent()->clientMove( $this->getId(), $cid, $cpw );
	}

	/**
	 * Kicks the client from his currently joined channel or from the server.
	 *
	 * @param  integer $reasonid
	 * @param  string  $reasonmsg
	 *
	 * @return void
	 */
	public function kick( $reasonid = Constant::KICK_CHANNEL, $reasonmsg = null )
	{
		return $this->getParent()->clientKick( $this->getId(), $reasonid, $reasonmsg );
	}

	/**
	 * Sends a poke message to the client.
	 *
	 * @param  string $msg
	 *
	 * @return void
	 */
	public function poke( $msg )
	{
		return $this->getParent()->clientPoke( $this->getId(), $msg );
	}

	/**
	 * Bans the client from the server. Please note that this will create two separate
	 * ban rules for the targeted clients IP address and his unique identifier.
	 *
	 * @param  integer $timeseconds
	 * @param  string  $reason
	 *
	 * @return array
	 */
	public function ban( $timeseconds = null, $reason = null )
	{
		return $this->getParent()->clientBan( $this->getId(), $timeseconds, $reason );
	}

	/**
	 * Returns a list of custom properties for the client.
	 *
	 * @return array
	 */
	public function customInfo()
	{
		return $this->getParent()->customInfo( $this[ "client_database_id" ] );
	}

	/**
	 * Returns an array containing the permission overview of the client.
	 *
	 * @param  integer $cid
	 *
	 * @return array
	 */
	public function permOverview( $cid )
	{
		return $this->execute(
			"permoverview",
			array( "cldbid" => $this[ "client_database_id" ], "cid" => $cid, "permid" => 0 )
		)->toArray();
	}

	/**
	 * Returns a list of permissions defined for the client.
	 *
	 * @param  boolean $permsid
	 *
	 * @return array
	 */
	public function permList( $permsid = false )
	{
		return $this->getParent()->clientPermList( $this[ "client_database_id" ], $permsid );
	}

	/**
	 * Adds a set of specified permissions to the client. Multiple permissions can be added by providing
	 * the three parameters of each permission.
	 *
	 * @param  integer $permid
	 * @param  integer $permvalue
	 * @param  integer $permskip
	 *
	 * @return void
	 */
	public function permAssign( $permid, $permvalue, $permskip = false )
	{
		return $this->getParent()->clientPermAssign( $this[ "client_database_id" ], $permid, $permvalue, $permskip );
	}

	/**
	 * Alias for permAssign().
	 *
	 * @deprecated
	 */
	public function permAssignByName( $permname, $permvalue, $permskip = false )
	{
		return $this->permAssign( $permname, $permvalue, $permskip );
	}

	/**
	 * Removes a set of specified permissions from a client. Multiple permissions can be removed at once.
	 *
	 * @param integer $permid
	 *
	 * @return void
	 */
	public function permRemove( $permid )
	{
		return $this->getParent()->clientPermRemove( $this[ "client_database_id" ], $permid );
	}

	/**
	 * Alias for permRemove().
	 *
	 * @deprecated
	 */
	public function permRemoveByName( $permname )
	{
		return $this->permRemove( $permname );
	}

	/**
	 * Sets the channel group of a client to the ID specified.
	 *
	 * @param  integer $cid
	 * @param  integer $cgid
	 *
	 * @return void
	 */
	public function setChannelGroup( $cid, $cgid )
	{
		return $this->getParent()->clientSetChannelGroup( $this[ "client_database_id" ], $cid, $cgid );
	}

	/**
	 * Adds the client to the server group specified with $sgid.
	 *
	 * @param  integer $sgid
	 *
	 * @return void
	 */
	public function addServerGroup( $sgid )
	{
		return $this->getParent()->serverGroupClientAdd( $sgid, $this[ "client_database_id" ] );
	}

	/**
	 * Removes the client from the server group specified with $sgid.
	 *
	 * @param  integer $sgid
	 *
	 * @return void
	 */
	public function remServerGroup( $sgid )
	{
		return $this->getParent()->serverGroupClientDel( $sgid, $this[ "client_database_id" ] );
	}

	/**
	 * Returns the possible name of the clients avatar.
	 *
	 * @return String
	 */
	public function avatarGetName()
	{
		return new String( "/avatar_" . $this[ "client_base64HashClientUID" ] );
	}

	/**
	 * Downloads and returns the clients avatar file content.
	 *
	 * @return String
	 */
	public function avatarDownload()
	{
		if ( $this[ "client_flag_avatar" ] == 0 ) {
			return;
		}

		$download = $this->getParent()->transferInitDownload( rand( 0x0000, 0xFFFF ), 0, $this->avatarGetName() );
		$transfer = Constant::factory( "filetransfer://" . $download[ "host" ] . ":" . $download[ "port" ] );

		return $transfer->download( $download[ "ftkey" ], $download[ "size" ] );
	}

	/**
	 * Returns a list of client connections using the same identity as this client.
	 *
	 * @return array
	 */
	public function getClones()
	{
		return $this->execute( "clientgetids", array( "cluid" => $this[ "client_unique_identifier" ] ) )->toAssocArray(
			"clid"
		);
	}

	/**
	 * Returns the revision/build number from the clients version string.
	 *
	 * @return integer
	 */
	public function getRev()
	{
		return $this[ "client_type" ] ? null : $this[ "client_version" ]->section( "[", 1 )->filterDigits();
	}

	/**
	 * Returns all server and channel groups the client is currently residing in.
	 *
	 * @return array
	 */
	public function memberOf()
	{
		$groups = array( $this->getParent()->channelGroupGetById( $this[ "client_channel_group_id" ] ) );

		foreach ( explode( ",", $this[ "client_servergroups" ] ) as $sgid ) {
			$groups[ ] = $this->getParent()->serverGroupGetById( $sgid );
		}

		return $groups;
	}

	/**
	 * Downloads and returns the clients icon file content.
	 *
	 * @return String
	 */
	public function iconDownload()
	{
		if ( $this->iconIsLocal( "client_icon_id" ) || $this[ "client_icon_id" ] == 0 ) {
			return;
		}

		$download = $this->getParent()->transferInitDownload(
			rand( 0x0000, 0xFFFF ),
			0,
			$this->iconGetName( "client_icon_id" )
		);
		$transfer = Constant::factory( "filetransfer://" . $download[ "host" ] . ":" . $download[ "port" ] );

		return $transfer->download( $download[ "ftkey" ], $download[ "size" ] );
	}

	/**
	 * Sends a plugin command to the client.
	 *
	 * @param  string $plugin
	 * @param  string $data
	 *
	 * @return void
	 */
	public function sendPluginCmd( $plugin, $data )
	{
		$this->execute(
			"plugincmd",
			array(
				"name"       => $plugin,
				"data"       => $data,
				"targetmode" => Constant::PLUGINCMD_CLIENT,
				"target"     => $this->getId()
			)
		);
	}

	/**
	 * @ignore
	 */
	protected function fetchNodeInfo()
	{
		if ( $this[ "client_type" ] == 1 ) {
			return;
		}

		$this->nodeInfo = array_merge(
			$this->nodeInfo,
			$this->execute( "clientinfo", array( "clid" => $this->getId() ) )->toList()
		);
	}

	/**
	 * Returns a unique identifier for the node which can be used as a HTML property.
	 *
	 * @return string
	 */
	public function getUniqueId()
	{
		return $this->getParent()->getUniqueId() . "_cl" . $this->getId();
	}

	/**
	 * Returns the name of a possible icon to display the node object.
	 *
	 * @return string
	 */
	public function getIcon()
	{
		if ( $this[ "client_type" ] ) {
			return "client_query";
		} elseif ( $this[ "client_away" ] ) {
			return "client_away";
		} elseif ( !$this[ "client_output_hardware" ] ) {
			return "client_snd_disabled";
		} elseif ( $this[ "client_output_muted" ] ) {
			return "client_snd_muted";
		} elseif ( !$this[ "client_input_hardware" ] ) {
			return "client_mic_disabled";
		} elseif ( $this[ "client_input_muted" ] ) {
			return "client_mic_muted";
		} elseif ( $this[ "client_is_channel_commander" ] ) {
			return $this[ "client_flag_talking" ] ? "client_cc_talk" : "client_cc_idle";
		} else {
			return $this[ "client_flag_talking" ] ? "client_talk" : "client_idle";
		}
	}

	/**
	 * Returns a symbol representing the node.
	 *
	 * @return string
	 */
	public function getSymbol()
	{
		return "@";
	}

	/**
	 * Returns a string representation of this node.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this[ "client_nickname" ];
	}
}

