<?php

header('Content-type: text/plain');

//--------

$array = [];
for ($i = 0; $i < 1000; ++$i) {
    $array[spl_object_hash(new StdClass)] = true;
}

$a = microtime(true);
for ($i = 0; $i < 10000; $i++) {
    isset($array['a']);
}

var_dump(microtime(true) - $a);


//--------

$array = [];
for ($i = 0; $i < 1000; ++$i) {
    $array[] = spl_object_hash(new StdClass);
}

$a = microtime(true);
for ($i = 0; $i < 10000; ++$i) {
    in_array('a', $array);
}
var_dump(microtime(true) - $a);
