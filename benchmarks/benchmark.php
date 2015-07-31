<?php

declare(strict_types = 1);

require 'bootstrap.php';

use streams\{
    Comparator,
    Func,
    Predicate,
    StreamBuilder
};

$count = 1000000;
$test = [];

for($i = 0; $i < $count; $i++) {
    $test[] = $i;
}


// ------------------------------------

$chrono->start('foreach: map, filter');
$res = [];
foreach($test as $v) {
    $v *= 2;
    if($v % 15 === 0) {
        continue;
    }
    $res[] = $v;
}
$chrono->stop();



$chrono->start('stream: map, filter');
StreamBuilder::fromArray($test)
    ->map(new class implements Func {
        public function apply($v) {
            return $v * 2;
        }
    })
    ->filter(new class implements Predicate {
        public function test($v): bool {
            return $v % 15 === 0;
        }
    })
    ->toArray();
$chrono->stop();
