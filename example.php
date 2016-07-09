<?php

//require('vendor/autoload.php');
require('lib/danog/PHP/Struct.php');

//var_dump(["nv", 61, 61, false, 333, 444, 232423, 234342, 243342423424, 234234234234, 234234234234, 234234234234, 34434, 344434, 2.2343, 3.03424, "dd"]);
var_dump(\danog\PHP\Struct::unpack("2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx", 
	   \danog\PHP\Struct::pack("2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx", 
"nv", 100, 100, false, 333, 444, 232423, 234342, 234234234234, 234234234234, 234234234234, 234234234234, 34434, 344434, 2.2343, 
3.03424, "dd"

)
));
