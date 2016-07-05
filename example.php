<?php

require('vendor/autoload.php');
$struct = new danog\PHP\Struct();

echo bin2hex($struct->pack("cc", "a", "s"));
