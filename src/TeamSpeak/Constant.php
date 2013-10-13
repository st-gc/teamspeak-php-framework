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

/**
 * Constant class all for TeamSpeak 3 PHP Framework objects.
 */
class Constant
{

	/**
	 * TeamSpeak 3 protocol welcome message.
	 */
	const READY = "TS3";

	/**
	 * TeamSpeak 3 protocol greeting message prefix.
	 */
	const GREET = "Welcome";

	/**
	 * TeamSpeak 3 protocol error message prefix.
	 */
	const ERROR = "error";

	/**
	 * TeamSpeak 3 protocol event message prefix.
	 */
	const EVENT = "notify";

	/**
	 * TeamSpeak 3 protocol server connection handler ID prefix.
	 */
	const SCHID = "selected";

	/**
	 * TeamSpeak 3 PHP Framework version.
	 */
	const LIB_VERSION = "1.1.22";

	/*@
		* TeamSpeak 3 protocol separators.
		*/

	const SEPARATOR_LINE = "\n"; //!< protocol line separator

	const SEPARATOR_LIST = "|"; //!< protocol list separator

	const SEPARATOR_CELL = " "; //!< protocol cell separator

	const SEPARATOR_PAIR = "="; //!< protocol pair separator

	/*@
		* TeamSpeak 3 log levels.
		*/

	const LOGLEVEL_CRITICAL = 0x00; //!< 0: these messages stop the program

	const LOGLEVEL_ERROR = 0x01; //!< 1: everything that is really bad

	const LOGLEVEL_WARNING = 0x02; //!< 2: everything that might be bad

	const LOGLEVEL_DEBUG = 0x03; //!< 3: output that might help find a problem

	const LOGLEVEL_INFO = 0x04; //!< 4: informational output

	const LOGLEVEL_DEVEL = 0x05; //!< 5: development output

	/*@
		* TeamSpeak 3 token types.
		*/

	const TOKEN_SERVERGROUP = 0x00; //!< 0: server group token  (id1={groupID} id2=0)

	const TOKEN_CHANNELGROUP = 0x01; //!< 1: channel group token (id1={groupID} id2={channelID})

	/*@
		* TeamSpeak 3 codec identifiers.
		*/

	const CODEC_SPEEX_NARROWBAND = 0x00; //!< 0: speex narrowband     (mono, 16bit, 8kHz)

	const CODEC_SPEEX_WIDEBAND = 0x01; //!< 1: speex wideband       (mono, 16bit, 16kHz)

	const CODEC_SPEEX_ULTRAWIDEBAND = 0x02; //!< 2: speex ultra-wideband (mono, 16bit, 32kHz)

	const CODEC_CELT_MONO = 0x03; //!< 3: celt mono            (mono, 16bit, 48kHz)

	const CODEC_OPUS_VOICE = 0x04; //!< 3: opus voice           (interactive)

	const CODEC_OPUS_MUSIC = 0x05; //!< 3: opus music           (interactive)

	/*@
		* TeamSpeak 3 codec encryption modes.
		*/

	const CODEC_CRYPT_INDIVIDUAL = 0x00; //!< 0: configure per channel

	const CODEC_CRYPT_DISABLED = 0x01; //!< 1: globally disabled

	const CODEC_CRYPT_ENABLED = 0x02; //!< 2: globally enabled

	/*@
		* TeamSpeak 3 kick reason types.
		*/

	const KICK_CHANNEL = 0x04; //!< 4: kick client from channel

	const KICK_SERVER = 0x05; //!< 5: kick client from server

	/*@
		* TeamSpeak 3 text message target modes.
		*/

	const TEXTMSG_CLIENT = 0x01; //!< 1: target is a client

	const TEXTMSG_CHANNEL = 0x02; //!< 2: target is a channel

	const TEXTMSG_SERVER = 0x03; //!< 3: target is a virtual server

	/*@
		* TeamSpeak 3 plugin command target modes.
		*/

	const PLUGINCMD_CHANNEL = 0x01; //!< 1: send plugincmd to all clients in current channel

	const PLUGINCMD_SERVER = 0x02; //!< 2: send plugincmd to all clients on server

	const PLUGINCMD_CLIENT = 0x03; //!< 3: send plugincmd to all given client ids

	const PLUGINCMD_CHANNEL_SUBSCRIBED = 0x04; //!< 4: send plugincmd to all subscribed clients in current channel

	/*@
		* TeamSpeak 3 host message modes.
		*/

	const HOSTMSG_NONE = 0x00; //!< 0: display no message

	const HOSTMSG_LOG = 0x01; //!< 1: display message in chatlog

	const HOSTMSG_MODAL = 0x02; //!< 2: display message in modal dialog

	const HOSTMSG_MODALQUIT = 0x03; //!< 3: display message in modal dialog and close connection

	/*@
		* TeamSpeak 3 host banner modes.
		*/

	const HOSTBANNER_NO_ADJUST = 0x00; //!< 0: do not adjust

	const HOSTBANNER_IGNORE_ASPECT = 0x01; //!< 1: adjust but ignore aspect ratio

	const HOSTBANNER_KEEP_ASPECT = 0x02; //!< 2: adjust and keep aspect ratio

	/*@
		* TeamSpeak 3 client identification types.
		*/

	const CLIENT_TYPE_REGULAR = 0x00; //!< 0: regular client

	const CLIENT_TYPE_SERVERQUERY = 0x01; //!< 1: query client

	/*@
		* TeamSpeak 3 permission group database types.
		*/

	const GROUP_DBTYPE_TEMPLATE = 0x00; //!< 0: template group     (used for new virtual servers)

	const GROUP_DBTYPE_REGULAR = 0x01; //!< 1: regular group      (used for regular clients)

	const GROUP_DBTYPE_SERVERQUERY = 0x02; //!< 2: global query group (used for ServerQuery clients)

	/*@
		* TeamSpeak 3 permission group name modes.
		*/

	const GROUP_NAMEMODE_HIDDEN = 0x00; //!< 0: display no name

	const GROUP_NAMEMODE_BEFORE = 0x01; //!< 1: display name before client nickname

	const GROUP_NAMEMODE_BEHIND = 0x02; //!< 2: display name after client nickname

	/*@
		* TeamSpeak 3 permission group identification types.
		*/

	const GROUP_IDENTIFIY_STRONGEST = 0x01; //!< 1: identify most powerful group

	const GROUP_IDENTIFIY_WEAKEST = 0x02; //!< 2: identify weakest group

	/*@
		* TeamSpeak 3 permission types.
		*/

	const PERM_TYPE_SERVERGROUP = 0x00; //!< 0: server group permission

	const PERM_TYPE_CLIENT = 0x01; //!< 1: client specific permission

	const PERM_TYPE_CHANNEL = 0x02; //!< 2: channel specific permission

	const PERM_TYPE_CHANNELGROUP = 0x03; //!< 3: channel group permission

	const PERM_TYPE_CHANNELCLIENT = 0x04; //!< 4: channel-client specific permission

	/*@
		* TeamSpeak 3 permission categories.
		*/

	const PERM_CAT_GLOBAL = 0x10; //!< 00010000: global permissions

	const PERM_CAT_GLOBAL_INFORMATION = 0x11; //!< 00010001: global permissions -> global information

	const PERM_CAT_GLOBAL_SERVER_MGMT = 0x12; //!< 00010010: global permissions -> virtual server management

	const PERM_CAT_GLOBAL_ADM_ACTIONS = 0x13; //!< 00010011: global permissions -> global administrative actions

	const PERM_CAT_GLOBAL_SETTINGS = 0x14; //!< 00010100: global permissions -> global settings

	const PERM_CAT_SERVER = 0x20; //!< 00100000: virtual server permissions

	const PERM_CAT_SERVER_INFORMATION = 0x21; //!< 00100001: virtual server permissions -> virtual server information

	const PERM_CAT_SERVER_ADM_ACTIONS = 0x22; //!< 00100010: virtual server permissions -> virtual server administrative actions

	const PERM_CAT_SERVER_SETTINGS = 0x23; //!< 00100011: virtual server permissions -> virtual server settings

	const PERM_CAT_CHANNEL = 0x30; //!< 00110000: channel permissions

	const PERM_CAT_CHANNEL_INFORMATION = 0x31; //!< 00110001: channel permissions -> channel information

	const PERM_CAT_CHANNEL_CREATE = 0x32; //!< 00110010: channel permissions -> create channels

	const PERM_CAT_CHANNEL_MODIFY = 0x33; //!< 00110011: channel permissions -> edit channels

	const PERM_CAT_CHANNEL_DELETE = 0x34; //!< 00110100: channel permissions -> delete channels

	const PERM_CAT_CHANNEL_ACCESS = 0x35; //!< 00110101: channel permissions -> access channels

	const PERM_CAT_GROUP = 0x40; //!< 01000000: group permissions

	const PERM_CAT_GROUP_INFORMATION = 0x41; //!< 01000001: group permissions -> group information

	const PERM_CAT_GROUP_CREATE = 0x42; //!< 01000010: group permissions -> create groups

	const PERM_CAT_GROUP_MODIFY = 0x43; //!< 01000011: group permissions -> edit groups

	const PERM_CAT_GROUP_DELETE = 0x44; //!< 01000100: group permissions -> delete groups

	const PERM_CAT_CLIENT = 0x50; //!< 01010000: client permissions

	const PERM_CAT_CLIENT_INFORMATION = 0x51; //!< 01010001: client permissions -> client information

	const PERM_CAT_CLIENT_ADM_ACTIONS = 0x52; //!< 01010010: client permissions -> client administrative actions

	const PERM_CAT_CLIENT_BASICS = 0x53; //!< 01010011: client permissions -> client basic communication

	const PERM_CAT_CLIENT_MODIFY = 0x54; //!< 01010100: client permissions -> edit clients

	const PERM_CAT_FILETRANSFER = 0x60; //!< 01100000: file transfer permissions

	const PERM_CAT_NEEDED_MODIFY_POWER = 0xFF; //!< 11111111: needed permission modify power (grant) permissions

	/*@
		* TeamSpeak 3 file types.
		*/

	const FILE_TYPE_DIRECTORY = 0x00; //!< 0: file is directory

	const FILE_TYPE_REGULAR = 0x01; //!< 1: file is regular

	/*@
		* TeamSpeak 3 server snapshot types.
		*/

	const SNAPSHOT_STRING = 0x00; //!< 0: default string

	const SNAPSHOT_BASE64 = 0x01; //!< 1: base64 string

	const SNAPSHOT_HEXDEC = 0x02; //!< 2: hexadecimal string

	/*@
		* TeamSpeak 3 channel spacer types.
		*/

	const SPACER_SOLIDLINE = 0x00; //!< 0: solid line

	const SPACER_DASHLINE = 0x01; //!< 1: dash line

	const SPACER_DOTLINE = 0x02; //!< 2: dot line

	const SPACER_DASHDOTLINE = 0x03; //!< 3: dash dot line

	const SPACER_DASHDOTDOTLINE = 0x04; //!< 4: dash dot dot line

	const SPACER_CUSTOM = 0x05; //!< 5: custom format

	/*@
		* TeamSpeak 3 channel spacer alignments.
		*/

	const SPACER_ALIGN_LEFT = 0x00; //!< 0: alignment left

	const SPACER_ALIGN_RIGHT = 0x01; //!< 1: alignment right

	const SPACER_ALIGN_CENTER = 0x02; //!< 2: alignment center

	const SPACER_ALIGN_REPEAT = 0x03; //!< 3: repeat until the whole line is filled

	/*@
		* TeamSpeak 3 reason identifiers.
		*/

	const REASON_NONE = 0x00; //!<  0: no reason

	const REASON_MOVE = 0x01; //!<  1: channel switched or moved

	const REASON_SUBSCRIPTION = 0x02; //!<  2: subscription added or removed

	const REASON_TIMEOUT = 0x03; //!<  3: client connection timed out

	const REASON_CHANNEL_KICK = 0x04; //!<  4: client kicked from channel

	const REASON_SERVER_KICK = 0x05; //!<  5: client kicked from server

	const REASON_SERVER_BAN = 0x06; //!<  6: client banned from server

	const REASON_SERVER_STOP = 0x07; //!<  7: server stopped

	const REASON_DISCONNECT = 0x08; //!<  8: client disconnected

	const REASON_CHANNEL_UPDATE = 0x09; //!<  9: channel information updated

	const REASON_CHANNEL_EDIT = 0x0A; //!< 10: channel information edited

	const REASON_DISCONNECT_SHUTDOWN = 0x0B; //!< 11: client disconnected on server shutdown

	/**
	 * Stores an array containing various chars which need to be escaped while communicating
	 * with a TeamSpeak 3 Server.
	 *
	 * @var array
	 */
	protected static $escape_patterns = array(
		"\\" => "\\\\", // backslash
		"/"  => "\\/", // slash
		" "  => "\\s", // whitespace
		"|"  => "\\p", // pipe
		";"  => "\\;", // semicolon
		"\a" => "\\a", // bell
		"\b" => "\\b", // backspace
		"\f" => "\\f", // formfeed
		"\n" => "\\n", // newline
		"\r" => "\\r", // carriage return
		"\t" => "\\t", // horizontal tab
		"\v" => "\\v" // vertical tab
	);

}