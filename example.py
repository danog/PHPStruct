import subprocess
from struct import unpack
import os

a = subprocess.Popen(["php", "example.php"], stdout=subprocess.PIPE).communicate()[0]
print(len(a))
unpack('2cxbxBx?xhxHxixIxlxLxqxQxnxNxfxdx2sx5pP', a)
