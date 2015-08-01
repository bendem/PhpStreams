--TEST--
Tests Stream::count
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    StreamBuilder
};

$result = StreamBuilder::of(1, 2, 3)
    ->count();

var_dump($result);
--EXPECT--
int(3)
