<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Helper;

use TeamSpeak\Constant;
use TeamSpeak\Model\String;

/**
 * Helper class for data conversion.
 */
class Convert
{
	/**
	 * Converts bytes to a human readable value.
	 *
	 * @param  integer $bytes
	 *
	 * @return string
	 */
	public static function bytes( $bytes )
	{
		$kbytes = sprintf( "%.02f", $bytes / 1024 );
		$mbytes = sprintf( "%.02f", $kbytes / 1024 );
		$gbytes = sprintf( "%.02f", $mbytes / 1024 );
		$tbytes = sprintf( "%.02f", $gbytes / 1024 );

		if ( $tbytes >= 1 ) {
			return $tbytes . " TB";
		}
		if ( $gbytes >= 1 ) {
			return $gbytes . " GB";
		}
		if ( $mbytes >= 1 ) {
			return $mbytes . " MB";
		}
		if ( $kbytes >= 1 ) {
			return $kbytes . " KB";
		}

		return $bytes . " B";
	}

	/**
	 * Converts seconds/milliseconds to a human readable value.
	 *
	 * @param  integer $seconds
	 * @param  boolean $is_ms
	 * @param  string  $format
	 *
	 * @return string
	 */
	public static function seconds( $seconds, $is_ms = false, $format = "%dD %02d:%02d:%02d" )
	{
		if ( $is_ms ) {
			$seconds = $seconds / 1000;
		}

		return sprintf(
			$format,
			$seconds / 60 / 60 / 24,
			( $seconds / 60 / 60 ) % 24,
			( $seconds / 60 ) % 60,
			$seconds % 60
		);
	}

	/**
	 * Converts a given codec ID to a human readable name.
	 *
	 * @param  integer $codec
	 *
	 * @return string
	 */
	public static function codec( $codec )
	{
		if ( $codec == Constant::CODEC_SPEEX_NARROWBAND ) {
			return "Speex Narrowband";
		}
		if ( $codec == Constant::CODEC_SPEEX_WIDEBAND ) {
			return "Speex Wideband";
		}
		if ( $codec == Constant::CODEC_SPEEX_ULTRAWIDEBAND ) {
			return "Speex Ultra-Wideband";
		}
		if ( $codec == Constant::CODEC_CELT_MONO ) {
			return "CELT Mono";
		}
		if ( $codec == Constant::CODEC_OPUS_VOICE ) {
			return "Opus Voice";
		}
		if ( $codec == Constant::CODEC_OPUS_MUSIC ) {
			return "Opus Music";
		}

		return "Unknown";
	}

	/**
	 * Converts a given group type ID to a human readable name.
	 *
	 * @param  integer $type
	 *
	 * @return string
	 */
	public static function groupType( $type )
	{
		if ( $type == Constant::GROUP_DBTYPE_TEMPLATE ) {
			return "Template";
		}
		if ( $type == Constant::GROUP_DBTYPE_REGULAR ) {
			return "Regular";
		}
		if ( $type == Constant::GROUP_DBTYPE_SERVERQUERY ) {
			return "ServerQuery";
		}

		return "Unknown";
	}

	/**
	 * Converts a given permission type ID to a human readable name.
	 *
	 * @param  integer $type
	 *
	 * @return string
	 */
	public static function permissionType( $type )
	{
		if ( $type == Constant::PERM_TYPE_SERVERGROUP ) {
			return "Server Group";
		}
		if ( $type == Constant::PERM_TYPE_CLIENT ) {
			return "Client";
		}
		if ( $type == Constant::PERM_TYPE_CHANNEL ) {
			return "Channel";
		}
		if ( $type == Constant::PERM_TYPE_CHANNELGROUP ) {
			return "Channel Group";
		}
		if ( $type == Constant::PERM_TYPE_CHANNELCLIENT ) {
			return "Channel Client";
		}

		return "Unknown";
	}

	/**
	 * Converts a given permission category value to a human readable name.
	 *
	 * @param  integer $pcat
	 *
	 * @return string
	 */
	public static function permissionCategory( $pcat )
	{
		if ( $pcat == Constant::PERM_CAT_GLOBAL ) {
			return "Global";
		}
		if ( $pcat == Constant::PERM_CAT_GLOBAL_INFORMATION ) {
			return "Global / Information";
		}
		if ( $pcat == Constant::PERM_CAT_GLOBAL_SERVER_MGMT ) {
			return "Global / Virtual Server Management";
		}
		if ( $pcat == Constant::PERM_CAT_GLOBAL_ADM_ACTIONS ) {
			return "Global / Administration";
		}
		if ( $pcat == Constant::PERM_CAT_GLOBAL_SETTINGS ) {
			return "Global / Settings";
		}
		if ( $pcat == Constant::PERM_CAT_SERVER ) {
			return "Virtual Server";
		}
		if ( $pcat == Constant::PERM_CAT_SERVER_INFORMATION ) {
			return "Virtual Server / Information";
		}
		if ( $pcat == Constant::PERM_CAT_SERVER_ADM_ACTIONS ) {
			return "Virtual Server / Administration";
		}
		if ( $pcat == Constant::PERM_CAT_SERVER_SETTINGS ) {
			return "Virtual Server / Settings";
		}
		if ( $pcat == Constant::PERM_CAT_CHANNEL ) {
			return "Channel";
		}
		if ( $pcat == Constant::PERM_CAT_CHANNEL_INFORMATION ) {
			return "Channel / Information";
		}
		if ( $pcat == Constant::PERM_CAT_CHANNEL_CREATE ) {
			return "Channel / Create";
		}
		if ( $pcat == Constant::PERM_CAT_CHANNEL_MODIFY ) {
			return "Channel / Modify";
		}
		if ( $pcat == Constant::PERM_CAT_CHANNEL_DELETE ) {
			return "Channel / Delete";
		}
		if ( $pcat == Constant::PERM_CAT_CHANNEL_ACCESS ) {
			return "Channel / Access";
		}
		if ( $pcat == Constant::PERM_CAT_GROUP ) {
			return "Group";
		}
		if ( $pcat == Constant::PERM_CAT_GROUP_INFORMATION ) {
			return "Group / Information";
		}
		if ( $pcat == Constant::PERM_CAT_GROUP_CREATE ) {
			return "Group / Create";
		}
		if ( $pcat == Constant::PERM_CAT_GROUP_MODIFY ) {
			return "Group / Modify";
		}
		if ( $pcat == Constant::PERM_CAT_GROUP_DELETE ) {
			return "Group / Delete";
		}
		if ( $pcat == Constant::PERM_CAT_CLIENT ) {
			return "Client";
		}
		if ( $pcat == Constant::PERM_CAT_CLIENT_INFORMATION ) {
			return "Client / Information";
		}
		if ( $pcat == Constant::PERM_CAT_CLIENT_ADM_ACTIONS ) {
			return "Client / Admin";
		}
		if ( $pcat == Constant::PERM_CAT_CLIENT_BASICS ) {
			return "Client / Basics";
		}
		if ( $pcat == Constant::PERM_CAT_CLIENT_MODIFY ) {
			return "Client / Modify";
		}
		if ( $pcat == Constant::PERM_CAT_FILETRANSFER ) {
			return "File Transfer";
		}
		if ( $pcat == Constant::PERM_CAT_NEEDED_MODIFY_POWER ) {
			return "Grant";
		}

		return "Unknown";
	}

	/**
	 * Converts a given log level ID to a human readable name and vice versa.
	 *
	 * @param  mixed $level
	 *
	 * @return string
	 */
	public static function logLevel( $level )
	{
		if ( is_numeric( $level ) ) {
			if ( $level == Constant::LOGLEVEL_CRITICAL ) {
				return "CRITICAL";
			}
			if ( $level == Constant::LOGLEVEL_ERROR ) {
				return "ERROR";
			}
			if ( $level == Constant::LOGLEVEL_DEBUG ) {
				return "DEBUG";
			}
			if ( $level == Constant::LOGLEVEL_WARNING ) {
				return "WARNING";
			}
			if ( $level == Constant::LOGLEVEL_INFO ) {
				return "INFO";
			}

			return "DEVELOP";
		} else {
			if ( strtoupper( $level ) == "CRITICAL" ) {
				return Constant::LOGLEVEL_CRITICAL;
			}
			if ( strtoupper( $level ) == "ERROR" ) {
				return Constant::LOGLEVEL_ERROR;
			}
			if ( strtoupper( $level ) == "DEBUG" ) {
				return Constant::LOGLEVEL_DEBUG;
			}
			if ( strtoupper( $level ) == "WARNING" ) {
				return Constant::LOGLEVEL_WARNING;
			}
			if ( strtoupper( $level ) == "INFO" ) {
				return Constant::LOGLEVEL_INFO;
			}

			return Constant::LOGLEVEL_DEVEL;
		}
	}

	/**
	 * Converts a specified log entry string into an array containing the data.
	 *
	 * @param  string $entry
	 *
	 * @return array
	 */
	public static function logEntry( $entry )
	{
		$parts = explode( "|", $entry, 5 );
		$array = array();

		if ( count( $parts ) != 5 ) {
			$array[ "timestamp" ] = 0;
			$array[ "level" ]     = Constant::LOGLEVEL_ERROR;
			$array[ "channel" ]   = "ParamParser";
			$array[ "server_id" ] = "";
			$array[ "msg" ]       = String::factory( "convert error (" . trim( $entry ) . ")" );
			$array[ "msg_plain" ] = $entry;
			$array[ "malformed" ] = true;
		} else {
			$array[ "timestamp" ] = strtotime( trim( $parts[ 0 ] ) );
			$array[ "level" ]     = self::logLevel( trim( $parts[ 1 ] ) );
			$array[ "channel" ]   = trim( $parts[ 2 ] );
			$array[ "server_id" ] = trim( $parts[ 3 ] );
			$array[ "msg" ]       = String::factory( trim( $parts[ 4 ] ) );
			$array[ "msg_plain" ] = $entry;
			$array[ "malformed" ] = false;
		}

		return $array;
	}

	/**
	 * Converts a given string to a ServerQuery password hash.
	 *
	 * @param  string $plain
	 *
	 * @return string
	 */
	public static function password( $plain )
	{
		return base64_encode( sha1( $plain, true ) );
	}

	/**
	 * Returns a client-like formatted version of the TeamSpeak 3 version string.
	 *
	 * @param  string $version
	 * @param  string $format
	 *
	 * @return string
	 */
	public static function version( $version, $format = "Y-m-d h:i:s" )
	{
		if ( !$version instanceof String ) {
			$version = new String( $version );
		}

		$buildno = $version->section( "[", 1 )->filterDigits()->toInt();

		return ( $buildno <= 15001 ) ? $version : $version->section( "[" )->append(
			"(" . date( $format, $buildno ) . ")"
		);
	}

	/**
	 * Returns a client-like short-formatted version of the TeamSpeak 3 version string.
	 *
	 * @param  string $version
	 *
	 * @return string
	 */
	public static function versionShort( $version )
	{
		if ( !$version instanceof String ) {
			$version = new String( $version );
		}

		return $version->section( " ", 0 );
	}

	/**
	 * Tries to detect the type of an image by a given string and returns it.
	 *
	 * @param  string $binary
	 *
	 * @return string
	 */
	public static function imageMimeType( $binary )
	{
		if ( !preg_match(
			'/\A(?:(\xff\xd8\xff)|(GIF8[79]a)|(\x89PNG\x0d\x0a)|(BM)|(\x49\x49(\x2a\x00|\x00\x4a))|(FORM.{4}ILBM))/',
			$binary,
			$matches
		)
		) {
			return "application/octet-stream";
		}

		$type = array(
			1 => "image/jpeg",
			2 => "image/gif",
			3 => "image/png",
			4 => "image/x-windows-bmp",
			5 => "image/tiff",
			6 => "image/x-ilbm",
		);

		return $type[ count( $matches ) - 1 ];
	}
}
