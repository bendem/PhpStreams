--TEST--
Tests Stream::concat
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    StreamBuilder
};

$result = StreamBuilder::of(1, 2, 3)
    ->concat(new \ArrayIterator([4, 5, 6]))
    ->toArray();
var_dump($result);
--EXPECT--
array(6) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
  [3]=>
  int(4)
  [4]=>
  int(5)
  [5]=>
  int(6)
}
