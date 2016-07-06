<?php

namespace danog\PHP;

/**
 * PHPStruct
 * PHP implementation of Python's struct module.
 * This library was created to help me develop a [client for the mtproto protocol](https://github.com/danog/MadelineProto).  
 * The functions and the formats are exactly the ones used in python's struct (https://docs.python.org/2/library/struct.html)
 * For now custom byte size is not fully supported, as well as p and P formats.
 *
 * @package		phpstruct
 * @author		Daniil Gentili <daniil@daniil.it>
 * @license		MIT license
*/
// Main class
class Struct {
	/**
	 * Constructor
	 *
	 * Sets modifiers and gets endianness
	 *
	 */
	public function __construct(){
		$this->BIG_ENDIAN = (pack('L', 1) === pack('N', 1));
		$this->MODIFIERS = [
			"<" => ["BIG_ENDIAN" => false],
			">" => ["BIG_ENDIAN" => true],
			"!" => ["BIG_ENDIAN" => true],
			"@" => ["BIG_ENDIAN" => $this->BIG_ENDIAN],
			"=" => ["BIG_ENDIAN" => $this->BIG_ENDIAN]
		];
		$this->FORMATS = [
			// These formats need to be modified after/before encoding/decoding.
			"p" => "p", // “Pascal string”, meaning a short variable-length string stored in a fixed number of bytes, given by the count. The first byte stored is the length of the string, or 255, whichever is smaller. The bytes of the string follow. If the string passed in to pack() is too long (longer than the count minus 1), only the leading count-1 bytes of the string are stored. If the string is shorter than count-1, it is padded with null bytes so that exactly count bytes in all are used. Note that for unpack(), the 'p' format character consumes count bytes, but that the string returned can never contain more than 255 characters.
			"P" => "P", // integer or long integer, depending on the size needed to hold a pointer when it has been cast to an integer type. A NULL pointer will always be returned as the Python integer 0. When packing pointer-sized values, Python integer or long integer objects may be used. For example, the Alpha and Merced processors use 64-bit pointer values, meaning a Python long integer will be used to hold the pointer; other platforms use 32-bit pointers and will use a Python integer.

			// These formats have automatical byte size, this must be fixed.
			"i" => "i", // should be 4 (32 bit)
			"I" => "I", // should be 4 (32 bit)
			"f" => "f", // should be 4 (32 bit)
			"d" => "d", // should be 8 (64 bit)

			// These formats should work exactly as in python's struct (same byte size, etc).
			"c" => "a",
			"?" => "c",
			"x" => "x",
			"b" => "c",
			"B" => "C",
			"h" => "s",
			"H" => "S",
			"l" => "l",
			"L" => "L",
			"q" => "q",
			"Q" => "Q",
			"s" => "a",
		];
		$this->LENGTH = [
			"p" => 1, 
			"P" => 8, 
			"i" => 4, 
			"I" => 4, 
			"f" => 4, 
			"d" => 8, 
			// These formats should work exactly as in python's struct (same byte size, etc).
			"c" => 1,
			"?" => 1,
			"x" => 1,
			"b" => 1,
			"B" => 1,
			"h" => 2,
			"H" => 2,
			"l" => 8,
			"L" => 8,
			"q" => 8,
			"Q" => 8,
			"s" => 1,
		];
	} 

	/**
	 * ExceptionErrorHandler
	 *
	 * Error handler for pack and unpack
	 *
	 */
	public function ExceptionErrorHandler($errno = 0, $errstr = null, $errfile = null, $errline = null) {
		// If error is suppressed with @, don't throw an exception
		if (error_reporting() === 0) {
			return true; // return true to continue through the others error handlers
		}
		throw new StructException($errstr, $errno);
	}

	/**
	 * pack
	 *
	 * Packs data into bytes
	 *
	 * @param	$format		Format string
	 * @param	...$data	Parameters to encode
	 * @return 	Encoded data 
	 */
	public function pack($format, ...$data) {
		$result = null; // Data to return
		$packcommand = $this->parseformat($format, $this->array_total_strlen($data), $this->array_each_strlen($data)); // Get pack parameters

		set_error_handler([$this, 'ExceptionErrorHandler']);
		foreach ($packcommand as $key => $command) {
			try {
				$curresult = pack($this->FORMATS[$command["format"]].$command["count"], $data[$key]); // Pack current char
			} catch(StructException $e) {
				throw new StructException("An error occurred while packing (" . $e->getMessage() . " on offset " . $key . ").");
			}
			
			if(isset($command["modifiers"]["BIG_ENDIAN"]) && ((!$this->BIG_ENDIAN && $command["modifiers"]["BIG_ENDIAN"]) || ($this->BIG_ENDIAN && !$command["modifiers"]["BIG_ENDIAN"]))) $curresult = strrev($curresult); // Reverse if wrong endianness
			$result .= $curresult;
		}

		restore_error_handler();
		if(strlen($result) != $this->calcsize($format)) {
			throw new StructException("Length of generated data is different from length calculated using format string.");
		}
		return $result;
	}
	/**
	 * unpack
	 *
	 * Unpacks data into an array
	 *
	 * @param	$format	Format string
	 * @param	$data	Data to decode
	 * @return 	Decoded data 
	 */
	public function unpack($format, $data) {
		if(strlen($data) != $this->calcsize($format)) {
			throw new StructException("Length of given data is different from length calculated using format string.");
		}
		$result = []; // Data to return
		$packcommand = $this->parseformat($format, strlen($data)); // Get unpack parameters
		set_error_handler([$this, 'ExceptionErrorHandler']);
		foreach ($packcommand as $key => $command) {
			if(isset($command["modifiers"]["BIG_ENDIAN"]) && ((!$this->BIG_ENDIAN && $command["modifiers"]["BIG_ENDIAN"]) || ($this->BIG_ENDIAN && !$command["modifiers"]["BIG_ENDIAN"]))) $data[$key] = strrev($data[$key]); // Reverse if wrong endianness
			try {
				$result[$key] = join('', unpack($this->FORMATS[$command["format"]].$command["count"], $data[$key])); // Unpack current char
			} catch(StructException $e) {
				throw new StructException("An error occurred while unpacking data (" . $e->getMessage() . " on offset " . $key . ").");
			}

			switch ($command["format"]) {
				case '?':
					if ($result[$key] == 0) $result[$key] = false; else $result[$key] = true;
					break;
				default:
					break;
			}
		}
		restore_error_handler();
		return $result;
	}
	

	/**
	 * parseformat
	 *
	 * Parses format string.
	 *
	 * @throws	StructException if format string is too long or there aren't enough parameters or if an unkown format or modifier is supplied.
	 * @param	$format		Format string to parse
	 * @param	$count		Number of chars in all objects to encode/decode
	 * @param	$arraycount	(Optional) Array containing the number of chars contained in each element of the array to pack
	 * @return 	Array with format and modifiers for each object to encode/decode
	 */
	public function parseformat($format, $count, $arraycount = null) {
		$formatcharcount = 0; // Current element to decode/encode
		$realformatcharcount = 0; // Current element to decode/encode (for real)
		$charcount = 0; // Current char

		$result = []; // Array with the results
		foreach (str_split($format) as $offset => $currentformatchar) { // Current format char
			if(!isset($result[$realformatcharcount]) || !is_array($result[$realformatcharcount]))	{
				$result[$realformatcharcount] = []; // Create array for current element
			}

			$result[$realformatcharcount]["count"] = 0; // Set the count of the objects to decode for the current format char to 0

			if(isset($this->MODIFIERS[$realformatcharcount])) { // If current format char is a modifier
				$result[$realformatcharcount]["modifiers"] = $this->MODIFIERS[$realformatcharcount]; // Set the modifiers for the current format char
			} else if(is_int($realformatcharcount) && ($realformatcharcount > 0 || $realformatcharcount <= 9)) {
				$result[$realformatcharcount]["count"] .= $realformatcharcount; // Set the count for the current format char
			} else if(isset($this->FORMATS[$realformatcharcount])) {
				if(!isset($result[$realformatcharcount]["count"]) || $result[$realformatcharcount]["count"] == 0 || $result[$realformatcharcount]["count"] == null) {
					$result[$realformatcharcount]["count"] = 1; // Set count to 1 if something's wrong.
				}
				$result[$realformatcharcount]["format"] = $realformatcharcount; // Set format
				if($arraycount !== null) {
					if($formatcharcount + 1 > count($arraycount)) {
						throw new StructException("Format string too long or not enough parameters at offset ".$offset.".");
					}
					if($result[$formatcharcount]["count"] > $arraycount[$formatcharcount]) {
						throw new StructException("Format string too long for offset ".$offset.".");
					}
				}
				if($currentformatchar != "x") {
					$formatcharcount++;
					$charcount += $result[$realformatcharcount]["count"];
				}
				if($charcount > $count) {
					throw new StructException("Format string too long or not enough chars (total char count is bigger than provided char count).");
				}
				$realformatcharcount++; // Increase element count

				
			} else throw new StructException("Unkown format or modifier supplied (".$currentformatchar." at offset ".$offset.").");
		}
		if($charcount != $count) {
			throw new StructException("Too many parameters or not enough format chars were specified.");
		}
		return $result;	
	}
	/**
	 * array_each_strlen
	 *
	 * Get length of each array element.
	 *
	 * @param	$array		Array to parse
	 * @return 	Array with lengths
	**/
	public function array_each_strlen($array) {
		foreach ($array as $key => &$value) {
			$value = strlen($array[$key]);
		}
		return $array;
	}
	/**
	 * array_total_strlen
	 *
	 * Get total length of every array element.
	 *
	 * @param	$array		Array to parse
	 * @return 	Integer with the total length
	**/
	public function array_total_strlen($array) {
		$count = 0;
		foreach ($array as $value) {
			$count += strlen($value);
		}
		return $count;
	}
	/**
	 * calcsize
	 *
	 * Return the size of the struct (and hence of the string) corresponding to the given format.

	 *
	 * @param	$format	Format string
	 * @return 	Int with size of the struct.
	 */
	public function calcsize($format) {
		$size = 0;
		foreach (str_split($format) as $offset => $currentformatchar) {
			if(isset($this->MODIFIERS[$currentformatchar])) {
				// Nuffink for now
			} else if(is_int($currentformatchar) && ($currentformatchar > 0 || $currentformatchar <= 9)) {
				$multiply .= $currentformatchar; // Set the count for the current format char
			} else if(isset($this->LENGTH[$currentformatchar])) {
				if(!isset($multiply) || $multiply == null) {
					$multiply = 1; // Set count to 1 if something's wrong.
				}
				$size += $multiply * $this->LENGTH[$currentformatchar];

				$multiply = null;
			} else throw new StructException("Unkown format or modifier supplied (".$currentformatchar." at offset ".$offset.").");
		}
		return $size;
	}
}

/* Just an exception class */
class StructException extends \Exception
{
}
