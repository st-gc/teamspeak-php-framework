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
 * Helper class for char handling.
 */
class Char
{

	/**
	 * Stores the original character.
	 *
	 * @var string
	 */
	protected $char = null;

	/**
	 * The Char constructor.
	 *
	 * @param $char
	 *
	 * @throws \TeamSpeak\Exception
	 * @return \TeamSpeak\Model\Char
	 */
	public function __construct( $char )
	{

		if ( strlen( $char ) != 1 ) {
			throw new Exception( "char parameter may not contain more or less than one character" );
		}

		$this->char = strval( $char );
	}

	/**
	 * Returns the Char based on a given hex value.
	 *
	 * @param  string $hex
	 *
	 * @throws Exception
	 * @return Char
	 */
	public static function fromHex( $hex )
	{

		if ( strlen( $hex ) != 2 ) {
			throw new Exception( "given parameter '" . $hex . "' is not a valid hexadecimal number" );
		}

		return new self( chr( hexdec( $hex ) ) );
	}

	/**
	 * Returns true if the character is a letter.
	 *
	 * @return boolean
	 */
	public function isLetter()
	{

		return ctype_alpha( $this->char );
	}

	/**
	 * Returns true if the character is a decimal digit.
	 *
	 * @return boolean
	 */
	public function isDigit()
	{

		return ctype_digit( $this->char );
	}

	/**
	 * Returns true if the character is a space.
	 *
	 * @return boolean
	 */
	public function isSpace()
	{

		return ctype_space( $this->char );
	}

	/**
	 * Returns true if the character is a mark.
	 *
	 * @return boolean
	 */
	public function isMark()
	{

		return ctype_punct( $this->char );
	}

	/**
	 * Returns true if the character is a control character (i.e. "\t").
	 *
	 * @return boolean
	 */
	public function isControl()
	{

		return ctype_cntrl( $this->char );
	}

	/**
	 * Returns true if the character is a printable character.
	 *
	 * @return boolean
	 */
	public function isPrintable()
	{

		return ctype_print( $this->char );
	}

	/**
	 * Returns true if the character is the Unicode character 0x0000 ("\0").
	 *
	 * @return boolean
	 */
	public function isNull()
	{

		return ( $this->char === "\0" ) ? true : false;
	}

	/**
	 * Returns the uppercase equivalent if the character is lowercase.
	 *
	 * @return Char
	 */
	public function toUpper()
	{

		return ( $this->isUpper() ) ? $this : new self( strtoupper( $this ) );
	}

	/**
	 * Returns true if the character is an uppercase letter.
	 *
	 * @return boolean
	 */
	public function isUpper()
	{

		return ( $this->char === strtoupper( $this->char ) ) ? true : false;
	}

	/**
	 * Returns the lowercase equivalent if the character is uppercase.
	 *
	 * @return Char
	 */
	public function toLower()
	{

		return ( $this->isLower() ) ? $this : new self( strtolower( $this ) );
	}

	/**
	 * Returns true if the character is a lowercase letter.
	 *
	 * @return boolean
	 */
	public function isLower()
	{

		return ( $this->char === strtolower( $this->char ) ) ? true : false;
	}

	/**
	 * Returns the Unicode value of the character.
	 *
	 * @return integer
	 */
	public function toUnicode()
	{

		$h = ord( $this->char{0} );

		if ( $h <= 0x7F ) {
			return $h;
		} else {
			if ( $h < 0xC2 ) {
				return false;
			} else {
				if ( $h <= 0xDF ) {
					return ( $h & 0x1F ) << 6 | ( ord( $this->char{1} ) & 0x3F );
				} else {
					if ( $h <= 0xEF ) {
						return ( $h & 0x0F ) << 12 | ( ord( $this->char{1} ) & 0x3F ) << 6 | ( ord(
								$this->char{2}
							) & 0x3F );
					} else {
						if ( $h <= 0xF4 ) {
							return ( $h & 0x0F ) << 18 | ( ord( $this->char{1} ) & 0x3F ) << 12 | ( ord(
									$this->char{2}
								) & 0x3F ) << 6 | ( ord( $this->char{3} ) & 0x3F );
						} else {
							return false;
						}
					}
				}
			}
		}
	}

	/**
	 * Returns the hexadecimal value of the char.
	 *
	 * @return string
	 */
	public function toHex()
	{

		return strtoupper( dechex( $this->toAscii() ) );
	}

	/**
	 * Returns the ascii value of the character.
	 *
	 * @return integer
	 */
	public function toAscii()
	{

		return ord( $this->char );
	}

	/**
	 * Returns the character as a standard string.
	 *
	 * @return string
	 */
	public function toString()
	{

		return $this->char;
	}

	/**
	 * Returns the integer value of the character.
	 *
	 * @return integer
	 */
	public function toInt()
	{

		return intval( $this->char );
	}

	/**
	 * Returns the character as a standard string.
	 *
	 * @return string
	 */
	public function __toString()
	{

		return $this->char;
	}
}
