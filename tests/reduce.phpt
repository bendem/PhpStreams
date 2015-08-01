--TEST--
Tests Stream::reduce
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    BiFunc,
    StreamBuilder
};

$result = StreamBuilder::of(1, 2, 3)
    ->reduce(new class implements BiFunc {
        function apply($obj1, $obj2) {
            return $obj1 + $obj2;
        }
    });

var_dump($result->isPresent());
var_dump($result->get());
--EXPECT--
bool(true)
int(6)
