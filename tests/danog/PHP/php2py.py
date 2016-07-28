#!/usr/bin/env python3
import subprocess
from struct import unpack
import os

a = subprocess.Popen(["php", "tests/danog/PHP/php2py.php"], stdout=subprocess.PIPE).communicate()[0]
print(len(a))
print(a)
print(unpack('2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2xsx5pP', a))
