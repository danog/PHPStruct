<?php

//require('vendor/autoload.php');
require('lib/danog/PHP/Struct.php');
$struct = new danog\PHP\Struct();


var_dump($struct->unpack(">2cx2c?iflh", $struct->pack(">2cx2c?iflh", "fa", "fs", true, 45, 2.1, 5000, 5005)));
