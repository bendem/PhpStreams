--TEST--
Tests Stream::findAny
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    StreamBuilder
};

$result = StreamBuilder::of(1, 2, 3)
    ->findAny();

var_dump($result->isPresent());
var_dump($result->get());
--EXPECTREGEX--
bool\(true\)
int\([1-3]\)
