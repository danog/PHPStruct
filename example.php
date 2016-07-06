<?php

//require('vendor/autoload.php');
require('lib/danog/PHP/Struct.php');
$struct = new danog\PHP\Struct();

echo bin2hex($struct->pack("cxc", "a", "s"));
