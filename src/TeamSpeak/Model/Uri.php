<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Model;

use TeamSpeak\Exception;

/**
 * Helper class for URI handling.
 */
class Uri
{

	/**
	 * Stores the URI scheme.
	 *
	 * @var string
	 */
	protected $scheme = null;

	/**
	 * Stores the URI username
	 *
	 * @var string
	 */
	protected $user = null;

	/**
	 * Stores the URI password.
	 *
	 * @var string
	 */
	protected $pass = null;

	/**
	 * Stores the URI host.
	 *
	 * @var string
	 */
	protected $host = null;

	/**
	 * Stores the URI port.
	 *
	 * @var string
	 */
	protected $port = null;

	/**
	 * Stores the URI path.
	 *
	 * @var string
	 */
	protected $path = null;

	/**
	 * Stores the URI query string.
	 *
	 * @var string
	 */
	protected $query = null;

	/**
	 * Stores the URI fragment string.
	 *
	 * @var string
	 */
	protected $fragment = null;

	/**
	 * Stores grammar rules for validation via regex.
	 *
	 * @var array
	 */
	protected $regex = array();

	/**
	 * The TeamSpeak3_Helper_Uri constructor.
	 *
	 * @param  string $uri
	 *
	 * @throws Exception
	 * @return \TeamSpeak\Model\Uri
	 */
	public function __construct( $uri )
	{

		$uri = explode( ":", strval( $uri ), 2 );

		$this->scheme = strtolower( $uri[ 0 ] );
		$uriString    = isset( $uri[ 1 ] ) ? $uri[ 1 ] : "";

<<<<<<< HEAD
		if( !ctype_alnum( $this->scheme ) ) {
=======
		if ( !ctype_alnum( $this->scheme ) ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			throw new Exception( "invalid URI scheme '" . $this->scheme . "' supplied" );
		}

		/* grammar rules for validation */
		$this->regex[ "alphanum" ]   = "[^\W_]";
		$this->regex[ "escaped" ]    = "(?:%[\da-fA-F]{2})";
		$this->regex[ "mark" ]       = "[-_.!~*'()\[\]]";
		$this->regex[ "reserved" ]   = "[;\/?:@&=+$,]";
		$this->regex[ "unreserved" ] = "(?:" . $this->regex[ "alphanum" ] . "|" . $this->regex[ "mark" ] . ")";
		$this->regex[ "segment" ]    = "(?:(?:" . $this->regex[ "unreserved" ] . "|" . $this->regex[ "escaped" ] . "|[:@&=+$,;])*)";
		$this->regex[ "path" ]       = "(?:\/" . $this->regex[ "segment" ] . "?)+";
		$this->regex[ "uric" ]       = "(?:" . $this->regex[ "reserved" ] . "|" . $this->regex[ "unreserved" ] . "|" . $this->regex[ "escaped" ] . ")";

<<<<<<< HEAD
		if( strlen( $uriString ) > 0 ) {
			$this->parseUri( $uriString );
		}

		if( !$this->isValid() ) {
=======
		if ( strlen( $uriString ) > 0 ) {
			$this->parseUri( $uriString );
		}

		if ( !$this->isValid() ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			throw new Exception( "invalid URI supplied" );
		}
	}

	/**
	 * Parses the scheme-specific portion of the URI and place its parts into instance variables.
	 *
<<<<<<< HEAD
	 * @throws Exception
=======
	 * @param string $uriString
	 *
	 * @throws \TeamSpeak\Exception
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	 * @return void
	 */
	protected function parseUri( $uriString = '' )
	{

		$status = @preg_match( "~^((//)([^/?#]*))([^?#]*)(\?([^#]*))?(#(.*))?$~", $uriString, $matches );

<<<<<<< HEAD
		if( $status === false ) {
			throw new Exception( "URI scheme-specific decomposition failed" );
		}

		if( !$status ) {
=======
		if ( $status === false ) {
			throw new Exception( "URI scheme-specific decomposition failed" );
		}

		if ( !$status ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return;
		}

		$this->path     = ( isset( $matches[ 4 ] ) ) ? $matches[ 4 ] : '';
		$this->query    = ( isset( $matches[ 6 ] ) ) ? $matches[ 6 ] : '';
		$this->fragment = ( isset( $matches[ 8 ] ) ) ? $matches[ 8 ] : '';

		$status = @preg_match(
			"~^(([^:@]*)(:([^@]*))?@)?([^:]+)(:(.*))?$~",
			( isset( $matches[ 3 ] ) ) ? $matches[ 3 ] : "",
			$matches
		);

<<<<<<< HEAD
		if( $status === false ) {
			throw new Exception( "URI scheme-specific authority decomposition failed" );
		}

		if( !$status ) return;
=======
		if ( $status === false ) {
			throw new Exception( "URI scheme-specific authority decomposition failed" );
		}

		if ( !$status ) {
			return;
		}
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8

		$this->user = isset( $matches[ 2 ] ) ? $matches[ 2 ] : "";
		$this->pass = isset( $matches[ 4 ] ) ? $matches[ 4 ] : "";
		$this->host = isset( $matches[ 5 ] ) ? $matches[ 5 ] : "";
		$this->port = isset( $matches[ 7 ] ) ? $matches[ 7 ] : "";
	}

	/**
	 * Validate the current URI from the instance variables.
	 *
	 * @return boolean
	 */
	public function isValid()
	{

<<<<<<< HEAD
		return ( $this->checkUser() && $this->checkPass() && $this->checkHost() && $this->checkPort() && $this->checkPath(
			) && $this->checkQuery() && $this->checkFragment() );
=======
		return ( $this->checkUser() && $this->checkPass() && $this->checkHost() && $this->checkPort(
			) && $this->checkPath() && $this->checkQuery() && $this->checkFragment() );
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}

	/**
	 * Returns TRUE if the username is valid.
	 *
	 * @param  string $username
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function checkUser( $username = null )
	{

<<<<<<< HEAD
		if( $username === null ) {
			$username = $this->user;
		}

		if( strlen( $username ) == 0 ) {
=======
		if ( $username === null ) {
			$username = $this->user;
		}

		if ( strlen( $username ) == 0 ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return true;
		}

		$pattern = "/^(" . $this->regex[ "alphanum" ] . "|" . $this->regex[ "mark" ] . "|" . $this->regex[ "escaped" ] . "|[;:&=+$,])+$/";
		$status  = @preg_match( $pattern, $username );

<<<<<<< HEAD
		if( $status === false ) {
=======
		if ( $status === false ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			throw new Exception( "URI username validation failed" );
		}

		return ( $status == 1 );
	}

	/**
	 * Returns TRUE if the password is valid.
	 *
	 * @param  string $password
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function checkPass( $password = null )
	{

<<<<<<< HEAD
		if( $password === null ) {
			$password = $this->pass;
		}

		if( strlen( $password ) == 0 ) {
=======
		if ( $password === null ) {
			$password = $this->pass;
		}

		if ( strlen( $password ) == 0 ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return true;
		}

		$pattern = "/^(" . $this->regex[ "alphanum" ] . "|" . $this->regex[ "mark" ] . "|" . $this->regex[ "escaped" ] . "|[;:&=+$,])+$/";
		$status  = @preg_match( $pattern, $password );

<<<<<<< HEAD
		if( $status === false ) {
=======
		if ( $status === false ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			throw new Exception( "URI password validation failed" );
		}

		return ( $status == 1 );
	}

	/**
	 * Returns TRUE if the host is valid.
	 *
	 * @param string $host
	 *
	 * @return boolean
	 */
	public function checkHost( $host = null )
	{

<<<<<<< HEAD
		if( $host === null ) {
=======
		if ( $host === null ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			$host = $this->host;
		}

		return true;
	}

	/**
	 * Returns TRUE if the port is valid.
	 *
	 * @param  integer $port
	 *
	 * @return boolean
	 */
	public function checkPort( $port = null )
	{

<<<<<<< HEAD
		if( $port === null ) {
=======
		if ( $port === null ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			$port = $this->port;
		}

		return true;
	}

	/**
	 * Returns TRUE if the path is valid.
	 *
	 * @param  string $path
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function checkPath( $path = null )
	{

<<<<<<< HEAD
		if( $path === null ) {
			$path = $this->path;
		}

		if( strlen( $path ) == 0 ) {
=======
		if ( $path === null ) {
			$path = $this->path;
		}

		if ( strlen( $path ) == 0 ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return true;
		}

		$pattern = "/^" . $this->regex[ "path" ] . "$/";
		$status  = @preg_match( $pattern, $path );

<<<<<<< HEAD
		if( $status === false ) {
=======
		if ( $status === false ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			throw new Exception( "URI path validation failed" );
		}

		return ( $status == 1 );
	}

	/**
	 * Returns TRUE if the query string is valid.
	 *
	 * @param  string $query
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function checkQuery( $query = null )
	{

<<<<<<< HEAD
		if( $query === null ) {
			$query = $this->query;
		}

		if( strlen( $query ) == 0 ) {
=======
		if ( $query === null ) {
			$query = $this->query;
		}

		if ( strlen( $query ) == 0 ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return true;
		}

		$pattern = "/^" . $this->regex[ "uric" ] . "*$/";
		$status  = @preg_match( $pattern, $query );

<<<<<<< HEAD
		if( $status === false ) {
=======
		if ( $status === false ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			throw new Exception( "URI query string validation failed" );
		}

		return ( $status == 1 );
	}

	/**
	 * Returns TRUE if the fragment string is valid.
	 *
	 * @param  string $fragment
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function checkFragment( $fragment = null )
	{

<<<<<<< HEAD
		if( $fragment === null ) {
			$fragment = $this->fragment;
		}

		if( strlen( $fragment ) == 0 ) {
=======
		if ( $fragment === null ) {
			$fragment = $this->fragment;
		}

		if ( strlen( $fragment ) == 0 ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return true;
		}

		$pattern = "/^" . $this->regex[ "uric" ] . "*$/";
		$status  = @preg_match( $pattern, $fragment );

<<<<<<< HEAD
		if( $status === false ) {
=======
		if ( $status === false ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			throw new Exception( "URI fragment validation failed" );
		}

		return ( $status == 1 );
	}

	/**
	 * Returns TRUE if a given URI is valid.
	 *
	 * @param  string $uri
	 *
	 * @return boolean
	 */
	public static function check( $uri )
	{

		try {
			$uri = new self( strval( $uri ) );
<<<<<<< HEAD
		} catch( Exception $e ) {
=======
		} catch ( Exception $e ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return false;
		}

		return $uri->valid();
	}

	/**
	 * Returns a specified instance parameter from the $_REQUEST array.
	 *
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	public static function getUserParam( $key, $default = null )
	{

		return ( array_key_exists( $key, $_REQUEST ) && !empty( $_REQUEST[ $key ] ) ) ? self::stripslashesRecursive(
			$_REQUEST[ $key ]
		) : $default;
	}

	/**
	 * Strips slashes from each element of an array using stripslashes().
	 *
	 * @param  mixed $var
	 *
	 * @return mixed
	 */
	protected static function stripslashesRecursive( $var )
	{

<<<<<<< HEAD
		if( !is_array( $var ) ) {
			return stripslashes( strval( $var ) );
		}

		foreach( $var as $key => $val ) {
			$var[ $key ] = ( is_array( $val ) ) ? stripslashesRecursive( $val ) : stripslashes( strval( $val ) );
=======
		if ( !is_array( $var ) ) {
			return stripslashes( strval( $var ) );
		}

		foreach ( $var as $key => $val ) {
			$var[ $key ] = ( is_array( $val ) ) ? self::stripslashesRecursive( $val ) : stripslashes( strval( $val ) );
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
		}

		return $var;
	}

	/**
	 * Returns a specified session parameter from the $_SESSION array.
	 *
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	public static function getSessParam( $key, $default = null )
	{

		return ( array_key_exists( $key, $_SESSION ) && !empty( $_SESSION[ $key ] ) ) ? $_SESSION[ $key ] : $default;
	}

	/**
	 * Returns an array containing the three main parts of a FQDN (Fully Qualified Domain Name), including the
	 * top-level domain, the second-level domains or hostname and the third-level domain.
	 *
	 * @param  string $hostname
	 *
	 * @return array
	 */
	public static function getFQDNParts( $hostname )
	{

<<<<<<< HEAD
		if( !preg_match(
=======
		if ( !preg_match(
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			"/^([a-z0-9][a-z0-9-]{0,62}\.)*([a-z0-9][a-z0-9-]{0,62}\.)+([a-z]{2,6})$/i",
			$hostname,
			$matches
		)
		) {
			return array();
		}

		$parts[ "tld" ] = $matches[ 3 ];
		$parts[ "2nd" ] = $matches[ 2 ];
		$parts[ "3rd" ] = $matches[ 1 ];

		return $parts;
	}

	/**
	 * Returns the applications base address.
	 *
	 * @return string
	 */
	public static function getBaseUri()
	{

<<<<<<< HEAD
		$scriptPath = new TeamSpeak3_Helper_String( dirname( self::getHostParam( "SCRIPT_NAME" ) ) );
=======
		$scriptPath = new String( dirname( self::getHostParam( "SCRIPT_NAME" ) ) );
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8

		return self::getHostUri()->append( ( $scriptPath == DIRECTORY_SEPARATOR ? "" : $scriptPath ) . "/" );
	}

	/**
	 * Returns a specified environment parameter from the $_SERVER array.
	 *
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	public static function getHostParam( $key, $default = null )
	{

		return ( array_key_exists( $key, $_SERVER ) && !empty( $_SERVER[ $key ] ) ) ? $_SERVER[ $key ] : $default;
	}

	/**
	 * Returns the applications host address.
	 *
	 * @return TeamSpeak3_Helper_String
	 */
	public static function getHostUri()
	{

		$sheme = ( self::getHostParam( "HTTPS" ) == "on" ) ? "https" : "http";

<<<<<<< HEAD
		$serverName = new TeamSpeak3_Helper_String( self::getHostParam( "HTTP_HOST" ) );
		$serverPort = self::getHostParam( "SERVER_PORT" );
		$serverPort = ( $serverPort != 80 && $serverPort != 443 ) ? ":" . $serverPort : "";

		if( $serverName->endsWith( $serverPort ) ) {
			$serverName = $serverName->replace( $serverPort, "" );
		}

		return new TeamSpeak3_Helper_String( $sheme . "://" . $serverName . $serverPort );
=======
		$serverName = new String( self::getHostParam( "HTTP_HOST" ) );
		$serverPort = self::getHostParam( "SERVER_PORT" );
		$serverPort = ( $serverPort != 80 && $serverPort != 443 ) ? ":" . $serverPort : "";

		if ( $serverName->endsWith( $serverPort ) ) {
			$serverName = $serverName->replace( $serverPort, "" );
		}

		return new String( $sheme . "://" . $serverName . $serverPort );
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}

	/**
	 * Returns the scheme.
	 *
	 * @param  mixed default
	 *
	 * @return TeamSpeak3_Helper_String
	 */
	public function getScheme( $default = null )
	{

<<<<<<< HEAD
		return ( $this->hasScheme() ) ? new TeamSpeak3_Helper_String( $this->scheme ) : $default;
=======
		return ( $this->hasScheme() ) ? new String( $this->scheme ) : $default;
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}

	/**
	 * Returns TRUE if the URI has a scheme.
	 *
	 * @return boolean
	 */
	public function hasScheme()
	{

		return strlen( $this->scheme ) ? true : false;
	}

	/**
	 * Returns the username.
	 *
<<<<<<< HEAD
	 * @param  mixed default
	 *
	 * @return TeamSpeak3_Helper_String
=======
	 * @param  mixed $default
	 *
	 * @return String
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	 */
	public function getUser( $default = null )
	{

<<<<<<< HEAD
		return ( $this->hasUser() ) ? new TeamSpeak3_Helper_String( $this->user ) : $default;
=======
		return ( $this->hasUser() ) ? new String( $this->user ) : $default;
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}

	/**
	 * Returns TRUE if the URI has a username.
	 *
	 * @return boolean
	 */
	public function hasUser()
	{

		return strlen( $this->user ) ? true : false;
	}

	/**
	 * Returns the password.
	 *
<<<<<<< HEAD
	 * @param  mixed default
	 *
	 * @return TeamSpeak3_Helper_String
=======
	 * @param  mixed $default
	 *
	 * @return String
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	 */
	public function getPass( $default = null )
	{

<<<<<<< HEAD
		return ( $this->hasPass() ) ? new TeamSpeak3_Helper_String( $this->pass ) : $default;
=======
		return ( $this->hasPass() ) ? new String( $this->pass ) : $default;
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}

	/**
	 * Returns TRUE if the URI has a password.
	 *
	 * @return boolean
	 */
	public function hasPass()
	{

		return strlen( $this->pass ) ? true : false;
	}

	/**
	 * Returns the host.
	 *
<<<<<<< HEAD
	 * @param  mixed default
	 *
	 * @return TeamSpeak3_Helper_String
=======
	 * @param  mixed $default
	 *
	 * @return String
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	 */
	public function getHost( $default = null )
	{

<<<<<<< HEAD
		return ( $this->hasHost() ) ? new TeamSpeak3_Helper_String( $this->host ) : $default;
=======
		return ( $this->hasHost() ) ? new String( $this->host ) : $default;
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}

	/**
	 * Returns TRUE if the URI has a host.
	 *
	 * @return boolean
	 */
	public function hasHost()
	{

		return strlen( $this->host ) ? true : false;
	}

	/**
	 * Returns the port.
	 *
	 * @param  mixed default
	 *
	 * @return integer
	 */
	public function getPort( $default = null )
	{

		return ( $this->hasPort() ) ? intval( $this->port ) : $default;
	}

	/**
	 * Returns TRUE if the URI has a port.
	 *
	 * @return boolean
	 */
	public function hasPort()
	{

		return strlen( $this->port ) ? true : false;
	}

	/**
	 * Returns the path.
	 *
<<<<<<< HEAD
	 * @param  mixed default
	 *
	 * @return TeamSpeak3_Helper_String
=======
	 * @param  mixed $default
	 *
	 * @return String
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	 */
	public function getPath( $default = null )
	{

<<<<<<< HEAD
		return ( $this->hasPath() ) ? new TeamSpeak3_Helper_String( $this->path ) : $default;
=======
		return ( $this->hasPath() ) ? new String( $this->path ) : $default;
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}

	/**
	 * Returns TRUE if the URI has a path.
	 *
	 * @return boolean
	 */
	public function hasPath()
	{

		return strlen( $this->path ) ? true : false;
	}

	/**
	 * Returns an array containing the query string elements.
	 *
	 * @param  mixed $default
	 *
	 * @return array
	 */
	public function getQuery( $default = array() )
	{

<<<<<<< HEAD
		if( !$this->hasQuery() ) {
=======
		if ( !$this->hasQuery() ) {
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			return $default;
		}

		parse_str( $this->query, $queryArray );

		return $queryArray;
	}

	/**
	 * Returns TRUE if the URI has a query string.
	 *
	 * @return boolean
	 */
	public function hasQuery()
	{

		return strlen( $this->query ) ? true : false;
	}

	/**
	 * Returns TRUE if the URI has a query variable.
	 *
<<<<<<< HEAD
=======
	 * @param $key
	 *
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	 * @return boolean
	 */
	public function hasQueryVar( $key )
	{

<<<<<<< HEAD
		if( !$this->hasQuery() ) return false;
=======
		if ( !$this->hasQuery() ) {
			return false;
		}
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8

		parse_str( $this->query, $queryArray );

		return array_key_exists( $key, $queryArray ) ? true : false;
	}

	/**
	 * Returns a single variable from the query string.
	 *
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	public function getQueryVar( $key, $default = null )
	{

<<<<<<< HEAD
		if( !$this->hasQuery() ) return $default;

		parse_str( $this->query, $queryArray );

		if( array_key_exists( $key, $queryArray ) ) {
			$val = $queryArray[ $key ];

			if( ctype_digit( $val ) ) {
				return intval( $val );
			} elseif( is_string( $val ) ) {
				return new TeamSpeak3_Helper_String( $val );
=======
		if ( !$this->hasQuery() ) {
			return $default;
		}

		parse_str( $this->query, $queryArray );

		if ( array_key_exists( $key, $queryArray ) ) {
			$val = $queryArray[ $key ];

			if ( ctype_digit( $val ) ) {
				return intval( $val );
			} elseif ( is_string( $val ) ) {
				return new String( $val );
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
			} else {
				return $val;
			}
		}

		return $default;
	}

	/**
	 * Returns the fragment.
	 *
<<<<<<< HEAD
	 * @param  mixed default
	 *
	 * @return TeamSpeak3_Helper_String
=======
	 * @param  mixed $default
	 *
	 * @return String
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	 */
	public function getFragment( $default = null )
	{

<<<<<<< HEAD
		return ( $this->hasFragment() ) ? new TeamSpeak3_Helper_String( $this->fragment ) : $default;
=======
		return ( $this->hasFragment() ) ? new String( $this->fragment ) : $default;
>>>>>>> f7b249fce37146989d856c68805f7af6899819e8
	}

	/**
	 * Returns TRUE if the URI has a fragment string.
	 *
	 * @return boolean
	 */
	public function hasFragment()
	{

		return strlen( $this->fragment ) ? true : false;
	}
}
