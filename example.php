<?php

//require('vendor/autoload.php');
require 'lib/danog/PHP/StructTools.php';
require 'lib/danog/PHP/StructClass.php';
require 'lib/danog/PHP/StructException.php';
require 'lib/danog/PHP/Struct.php';
//var_dump(["nv", 61, 61, false, 333, 444, 232423, 234342, 243342423424, 234234234234, 234234234234, 234234234234, 34434, 344434, 2.2343, 3.03424, "dd"]);
/*echo \danog\PHP\Struct::pack('2cbB?hHiIlLqQnNfd2s5pP',
'n', 'v', -127, 100, true, 333, 444, 232423, 234342, 999999999999, 999999999999, -888888888888,
888888888888, 34434, 344434, 2.2343,
3.03424, 'df', 'asdfghjkl', 1283912

);die;*/
$struct = new \danog\PHP\StructClass('2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx5pP');

var_dump($struct->unpack($struct->pack('n', 'v', -127, 100, true, 333, 444, 232423, 234342, 999999999999, 999999999999, -888888888888,888888888888, 34434, 344434, 2.2343, 3.03424, 'df', 'asdfghjkl', 1283912)));
die;
var_dump(\danog\PHP\Struct::unpack('2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx5pP',
       \danog\PHP\Struct::pack('2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx5pP',
'n', 'v', -127, 100, true, 333, 444, 232423, 234342, 999999999999, 999999999999, -888888888888,
888888888888, 34434, 344434, 2.2343,
3.03424, 'df', 'asdfghjkl', 1283912

)
));
var_dump(\danog\PHP\Struct::calcsize('f'));
var_dump(bin2hex(pack("Q", 
999999999999999)));
var_dump(bin2hex(\danog\PHP\Struct::pack('Q',
999998999999999)));
// 2c x b x B x ? x h x H x i x I x l x L x q x Q x n x N x f x d x 2s x
//print(struct.unpack("2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx", struct.pack("2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx", "nv", 100, 100, False, 333, 444, 232423, 234342, 234234234234, 234234234234, 234234234234, 234234234234, 34434, 344434, 2.2343, 3.03424, "dd")));
