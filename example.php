<?php

require('vendor/autoload.php');
$rightpack = new danog\RightPack\RightPacker();

echo bin2hex($rightpack->pack("cc", "a", "s"));
