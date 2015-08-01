--TEST--
Tests Stream::map
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    Func,
    StreamBuilder
};

$result = StreamBuilder::of(1, 2, 3)
    ->map(new class implements Func {
        function apply($obj) {
            return $obj * 2;
        }
    })
    ->toArray();
var_dump($result);
--EXPECT--
array(3) {
  [0]=>
  int(2)
  [1]=>
  int(4)
  [2]=>
  int(6)
}
