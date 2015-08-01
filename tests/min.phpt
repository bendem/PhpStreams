--TEST--
Tests Stream::min
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    StreamBuilder
};

$min = StreamBuilder::of(3, 5, 1, 4, 9, 0)
    ->min();

var_dump($min->get());
--EXPECT--
int(0)
