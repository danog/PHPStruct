<?php

//require('vendor/autoload.php');
require('lib/danog/PHP/Struct.php');
$struct = new danog\PHP\Struct();


var_dump($struct->unpack("2cx2c", $struct->pack("2cx2c", "fa", "fs")));
