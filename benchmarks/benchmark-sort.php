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

$chrono->start('foreach: map, filter, sort');
$res = [];
foreach($test as $v) {
    $v *= 2;
    if($v % 15 === 0) {
        continue;
    }
    $res[] = $v;
}
sort($res);
$chrono->stop();



$chrono->start('foreach: map, filter, usort');
$res = [];
foreach($test as $v) {
    $v *= 2;
    if($v % 15 === 0) {
        continue;
    }
    $res[] = $v;
}
usort($res, function($o1, $o2) { return $o1 <=> $o2; });
$chrono->stop();



$chrono->start('stream: map, filter, sorted');
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
    ->sorted()
    ->toArray();
$chrono->stop();



$chrono->start('stream: map, filter, sorted(Comparator)');
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
    ->sorted(new class implements Comparator {
        public function compare($o1, $o2): int {
            return $o1 <=> $o2;
        }
    })
    ->toArray();
$chrono->stop();
