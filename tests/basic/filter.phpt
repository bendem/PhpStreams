--TEST--
Tests Stream::filter
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    Predicate,
    StreamBuilder
};

$result = StreamBuilder::of(1, "foo", [])
    ->filter(new class implements Predicate {
        function test($obj): bool {
            return is_scalar($obj);
        }
    })
    ->toArray();
var_dump($result);
--EXPECT--
array(2) {
  [0]=>
  int(1)
  [1]=>
  string(3) "foo"
}
