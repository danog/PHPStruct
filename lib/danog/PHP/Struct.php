<?php

namespace danog\PHP;

/**
 * PHPStruct
 * PHP implementation of Python's struct module.
 * This library was created to help me develop a [client for the mtproto protocol](https://github.com/danog/MadelineProto).
 * The functions and the formats are exactly the ones used in python's struct (https://docs.python.org/3/library/struct.html)
 * For now custom byte size may not work properly on certain machines for the f and d formats.
 *
 * @author		Daniil Gentili <daniil@daniil.it>
 * @license		MIT license
 */
// Wrapper class
class Struct extends StructTools
{
    /**
     * pack.
     *
     * Packs data into bytes
     *
     * @param	...$data	Parameters to encode (may also contain format string)
     *
     * @return Encoded data
     */
    public function pack(...$data)
    {
        if (!(isset($this) && get_class($this) == __CLASS__) || $this->format == null) {
            $struct = new \danog\PHP\Struct(array_shift($data));
            return $struct->_pack(...$data);
        }

        return $this->_pack(...$data);
    }

    /**
     * unpack.
     *
     * Unpacks data into an array
     *
     * @param	$format	Format string
     * @param	$data	Data to decode
     *
     * @return Decoded data
     */
    public function unpack($format, $data = null)
    {
        if (!(isset($this) && get_class($this) == __CLASS__) || $this->format == null) {
            $struct = new \danog\PHP\Struct($format);
            return $struct->_unpack($data);
        }
        return $this->_unpack($format);
    }

    /**
     * calcsize.
     *
     * Return the size of the struct (and hence of the string) corresponding to the given format.

     *
     * @param	$format	Format string
     *
     * @return int with size of the struct.
     */
    public function calcsize($format = null)
    {
        if (!(isset($this) && get_class($this) == __CLASS__) || $this->format == null) {
            $struct = new \danog\PHP\Struct($format);
            return $struct->_calcsize();
        }
        return $this->_calcsize();
    }
}