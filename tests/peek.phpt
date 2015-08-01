--TEST--
Tests Stream::peek
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    Consumer,
    Func,
    StreamBuilder
};

StreamBuilder::of(1, 2, 3, 4, 5)
    ->map(new class implements Func {
        function apply($obj) {
            return $obj * 2;
        }
    })
    ->peek(new class implements Consumer {
        function accept($obj) {
            var_dump($obj);
        }
    })
    ->count();

--EXPECT--
int(2)
int(4)
int(6)
int(8)
int(10)
