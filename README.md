# rightpack class

Licensed under MIT.

PHP's pack() and unpack(), done the right way.

This library was created to help me develop a [client for the mtproto protocol](https://github.com/danog/MadelineProto).  

The format syntax is exactly the one used in python's struct (https://docs.python.org/2/library/struct.html)

For now custom byte size is not fully supported, as well as p and P formats.

## Installation

Install using composer:
```
composer require danog/rightpack
```

# Usage

```
require('vendor/autoload.php');
$rightpack = new \danog\RightPack\RightPacker();
```

[Daniil Gentili](http://daniil.it)
