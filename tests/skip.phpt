--TEST--
Tests Stream::skip
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    StreamBuilder
};

$result = StreamBuilder::of(1, 2, 3, 4, 5)
    ->skip(3)
    ->toArray();

var_dump($result);
--EXPECT--
array(2) {
  [0]=>
  int(4)
  [1]=>
  int(5)
}
