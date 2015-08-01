--TEST--
Tests Stream::flatMap
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    Func,
    StreamBuilder
};

$result = StreamBuilder::of(1, 2)
    ->flatMap(new class implements Func {
        public function apply($i) {
            return array_fill(0, $i, $i);
        }
    })
    ->toArray();

var_dump($result);
--EXPECT--
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(2)
}
