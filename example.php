<?php

//require('vendor/autoload.php');
require('lib/danog/PHP/Struct.php');
$struct = new danog\PHP\Struct();


var_dump($struct->unpack(">2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx", 
	   $struct->pack(">2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx", 
"nv", 61, 61, false, 333, 444, 232423, 234342, 243342423424, 234234234234, 234234234234, 234234234234, 34434, 344434, 2.2343, 3.03424, "dd"

)
));
