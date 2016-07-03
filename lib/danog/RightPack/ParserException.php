<?php
namespace danog\RightPack;

/**
 * RightPack
 *
 * PHP's pack() and unpack(), done the right way.
 * The format syntax is exactly the one used in python's struct (https://docs.python.org/2/library/struct.html)
 * For now custom byte size is not fully supported.
 *
 * @package		rightpack
 * @author		Daniil Gentili <daniil@daniil.it>
 * @license		MIT license
*/

/* Just an exception class */
class ParserException extends \Exception
{
}
