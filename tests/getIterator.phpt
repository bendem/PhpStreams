--TEST--
Tests Stream::getIterator
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    StreamBuilder
};

foreach(StreamBuilder::of(1, 2, 3) as $v) {
    var_dump($v);
}

foreach(StreamBuilder::of(1, 2, 3)->getIterator() as $v) {
    var_dump($v);
}
--EXPECT--
int(1)
int(2)
int(3)
int(1)
int(2)
int(3)
