--TEST--
Checks that operations are lazily executed
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    Consumer,
    Predicate,
    StreamBuilder
};

StreamBuilder::of(1, 2, 3)
    ->filter(new class implements Predicate {
        function test($obj): bool {
            return false;
        }
    })
    ->forEach(new class implements Consumer {
        function accept($obj) {
            var_dump($obj);
        }
    });
--EXPECT--
