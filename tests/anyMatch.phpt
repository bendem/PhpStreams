--TEST--
Tests Stream::anyMatch
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    Predicate,
    StreamBuilder
};

$result = StreamBuilder::of(1, 2, 3)
    ->anyMatch(new class implements Predicate {
        public function test($i): bool {
            return $i % 2 === 0;
        }
    });

var_dump($result);

$result = StreamBuilder::of(1, 3)
    ->anyMatch(new class implements Predicate {
        public function test($i): bool {
            return $i % 2 === 0;
        }
    });

var_dump($result);
--EXPECT--
bool(true)
bool(false)
