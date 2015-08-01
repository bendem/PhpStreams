--TEST--
Tests Stream::forEach
--FILE--
<?php
require './vendor/autoload.php';

use streams\{
    Consumer,
    StreamBuilder
};

StreamBuilder::of(1, "foo", [])
    ->forEach(new class implements Consumer {
        function accept($obj) {
            var_dump($obj);
        }
    });
--EXPECT--
int(1)
string(3) "foo"
array(0) {
}
