<?php

declare(strict_types = 1);

/**
 * + Skipping is weird, it should maybe be handled outside the pipeline
 *
 * + Find/match/min/max stuff don't go through the execute method, a specific
 *   one will be added
 *
 * + Need to replace OperationType with an enum when it's available
 *   (https://wiki.php.net/rfc/enum)
 *
 * + Write unit tests!
 *
 * + Maybe moving from foreach to while($it->valid()) {
 *       $it->current();
 *       $it->next();
 *   } would be wise?
 *
 * + Gotta find a way to sort stuff somehow
 *
 * + Flat maps are a pain in the ass
 *
 * + What to do with collect?
 *
 * + Utility to create classes for the interfaces from closures
 *
 */

require 'vendor/autoload.php';

use streams\{
    BiFunc,
    Consumer,
    Func,
    Predicate,
    StreamBuilder,
    Supplier
};

header('Content-type: text/plain');

function separate() {
    echo "\n-----\n\n";
}

echo "filtering, mapping, foreach\n";
StreamBuilder::fromArray(range(0, 15))
    ->filter(new class() implements Predicate {
        public function test($object): bool {
            return $object % 2 == 0;
        }
    })
    ->map(new class() implements Func {
        public function apply($object) {
            return $object * 2;
        }
    })
    ->forEach(new class() implements Consumer {
        public function accept($object) {
            var_dump($object);
        }
    });

separate();

echo "lazy execution\n";
$count = StreamBuilder::fromArray(range(0, 10))
    ->filter(new class() implements Predicate {
        public function test($i): bool {
            return false;
        }
    })
    ->map(new class() implements Func {
        public function apply($obj) {
            var_dump($obj);
        }
    })
    ->count();
var_dump($count);

separate();

echo "iterator\n";
foreach(StreamBuilder::fromArray(range(0, 2))->map(new class implements Func {
    public function apply($i) {
        return $i + 1;
    }
})->getIterator() as $v) {
    var_dump($v);
}

separate();

echo "distinct\n";
$count = StreamBuilder::fromArray(range(0, 10))
    ->map(new class implements Func {
        public function apply($i) {
            return $i % 2;
        }
    })
    ->distinct()
    ->count();
var_dump($count);

separate();

echo "multiple mapping, peeking\n";
StreamBuilder::of(1, 'foo', new StdClass)
    ->map(new class implements Func {
        public function apply($obj) {
            $a = new StdClass;
            $a->b = $obj;
            return $a;
        }
    })
    ->peek(new class implements Consumer {
        public function accept($obj) {
            var_dump($obj);
        }
    })
    ->map(new class implements Func {
        public function apply($obj) {
            return $obj->b;
        }
    })
    ->forEach(new class implements Consumer {
        public function accept($obj) {
            var_dump($obj);
        }
    });

separate();

echo "skipping, limitting\n";
StreamBuilder::fromArray(range(0, 20))
    ->skip(5)
    ->skip(6)
    ->limit(7)
    ->forEach(new class implements Consumer {
        public function accept($obj) {
            var_dump($obj);
        }
    });

separate();

echo "reducing\n";
$reduced = StreamBuilder::fromArray(range(1, 5))
    ->reduce(0, new class implements BiFunc {
        public function apply($prev, $val) {
            return $prev + $val;
        }
    });
var_dump($reduced);
