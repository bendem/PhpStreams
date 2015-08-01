--TEST--
Tests Stream::max
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    StreamBuilder
};

$max = StreamBuilder::of(3, 5, 1, 4, 9, 0)
    ->max();

var_dump($max->get());
--EXPECT--
int(9)
