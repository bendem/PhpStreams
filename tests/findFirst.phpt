--TEST--
Tests Stream::findFirst
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    StreamBuilder
};

$result = StreamBuilder::of(1, 2, 3)
    ->findFirst();

var_dump($result->isPresent());
var_dump($result->get());
--EXPECT--
bool(true)
int(1)
