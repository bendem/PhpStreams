--TEST--
Tests Stream::toArray
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    StreamBuilder
};

$result = StreamBuilder::of(1, 2, 3)
    ->toArray();

var_dump($result);
--EXPECT--
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
}
