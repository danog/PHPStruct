# PHPStruct class

[![Build Status](https://travis-ci.org/danog/PHPStruct.svg?branch=master)](https://travis-ci.org/danog/PHPStruct)

Licensed under MIT.

PHP implementation of Python's struct module.

This library was created to help me develop a [client for the mtproto protocol](https://github.com/danog/MadelineProto).  

The functions and the formats are exactly the ones used in python's struct (https://docs.python.org/2/library/struct.html)

For now custom byte size is not fully supported, as well as p and P formats.

## Installation

Install using composer:
```
composer require danog/phpstruct
```

# Usage

```
require('vendor/autoload.php');
$rightpack = new \danog\PHP\Struct();
```

[Daniil Gentili](http://daniil.it)
