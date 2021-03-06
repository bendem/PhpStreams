<?php

declare(strict_types = 1);

/**
 * + Skipping is weird, it should maybe be handled outside the pipeline
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
 * + If two calls to Stream::sorted follow each other, the first one second
 *   one should probably be ignored?
 *
 * + Implement all kinds of collectors
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

$debugConsumer = new class implements Consumer {
    public function accept($obj) {
        var_dump($obj);
    }
};

$oddPredicate = new class implements Predicate {
    public function test($i): bool {
        return $i % 2 === 0;
    }
};

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
    ->forEach($debugConsumer);

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
$a = new StdClass;
$count = StreamBuilder::of(1, 1, 3, true, false, true, 'hey', 'hey', [], new StdClass, $a, $a)
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
    ->peek($debugConsumer)
    ->map(new class implements Func {
        public function apply($obj) {
            return $obj->b;
        }
    })
    ->forEach($debugConsumer);

separate();

echo "skipping, limitting\n";
StreamBuilder::fromArray(range(0, 20))
    ->skip(5)
    ->skip(6)
    ->limit(7)
    ->forEach($debugConsumer);

separate();

echo "reducing\n";
$reduced = StreamBuilder::fromArray(range(1, 5))
    ->reduce(new class implements BiFunc {
        public function apply($prev, $val) {
            return $prev + $val;
        }
    });
var_dump($reduced->isPresent());
var_dump($reduced->get());

separate();

echo "min\n";
$min = StreamBuilder::of(3, 5, 1, 4, 9, 0)
    ->min();
var_dump($min->get());


separate();

echo "max\n";
$max = StreamBuilder::of(3, 5, 1, 4, 9, 0)
    ->max();
var_dump($max->get());

separate();

echo "findFirst\n";
$first = StreamBuilder::of(3, 4)
    ->findFirst();
var_dump($first->isPresent());
var_dump($first->get());

separate();

echo "findFirst (none present)\n";
$first = StreamBuilder::of()
    ->findFirst();
var_dump($first->isPresent());
// var_dump($first->get());

separate();

echo "findAny\n";
$first = StreamBuilder::of(5, 4, 6)
    ->sorted()
    ->findAny();
var_dump($first->isPresent());
var_dump($first->get());

separate();

echo "sorting\n";
StreamBuilder::of(3, 2, 4, 1, 5)
    ->sorted()
    ->forEach($debugConsumer);

separate();

echo "infinite stream\n";
StreamBuilder::generate((function() {
        $i = 0;
        while(true) {
            yield $i++;
        }
    })())
    ->skip(10)
    ->limit(15)
    ->forEach($debugConsumer);

separate();

echo "infinite sorted random stream\n";
StreamBuilder::generate((function() {
        while(true) {
            yield rand();
        }
    })())
    ->limit(15)
    ->sorted()
    ->forEach($debugConsumer);

separate();

echo "sort + limit\n";
StreamBuilder::of(2, 5, 1, 4, 0, 9, 8)
    ->sorted()
    ->limit(3)
    ->forEach($debugConsumer);

separate();

echo "limit + sort\n";
StreamBuilder::of(2, 5, 1, 4, 0, 9, 8)
    ->limit(3)
    ->sorted()
    ->forEach($debugConsumer);

separate();

echo "allMatch (true)\n";
$res = StreamBuilder::of(4, 6, 2, 0, 12)
    ->allMatch($oddPredicate);
var_dump($res);

separate();

echo "allMatch (false)\n";
$res = StreamBuilder::of(4, 6, 2, 0, 1)
    ->allMatch($oddPredicate);
var_dump($res);

separate();

echo "anyMatch (true)\n";
$res = StreamBuilder::of(4, 6, 5, 0, 1)
    ->anyMatch($oddPredicate);
var_dump($res);

separate();

echo "anyMatch (false)\n";
$res = StreamBuilder::of(1, 3, 5, 3, 9)
    ->anyMatch($oddPredicate);
var_dump($res);

separate();

echo "noneMatch (true)\n";
$res = StreamBuilder::of(1, 3, 5, 3, 9)
    ->noneMatch($oddPredicate);
var_dump($res);

separate();

echo "noneMatch (false)\n";
$res = StreamBuilder::of(1, 3, 5, 3, 2)
    ->noneMatch($oddPredicate);
var_dump($res);

separate();

echo "flatMap\n";
StreamBuilder::of(3, 2, 1)
    ->flatMap(new class implements Func {
        public function apply($i) {
            return StreamBuilder::fromArray(array_fill(0, $i, $i));
        }
    })
    ->forEach($debugConsumer);

separate();

echo "flatMap + sorted\n";
StreamBuilder::of(3, 2, 1)
    ->flatMap(new class implements Func {
        public function apply($i) {
            return StreamBuilder::fromArray(array_fill(0, $i, $i));
        }
    })
    ->sorted()
    ->forEach($debugConsumer);

separate();

echo "limit + flatMap\n";
StreamBuilder::of(3, 2, 1)
    ->limit(2)
    ->flatMap(new class implements Func {
        public function apply($i) {
            return StreamBuilder::fromArray(array_fill(0, $i, $i));
        }
    })
    ->forEach($debugConsumer);

separate();

echo "skip + flatMap\n";
StreamBuilder::of(3, 2, 1)
    ->skip(1)
    ->flatMap(new class implements Func {
        public function apply($i) {
            return StreamBuilder::fromArray(array_fill(0, $i, $i));
        }
    })
    ->forEach($debugConsumer);

separate();

echo "flatMap + skip\n";
StreamBuilder::of(3, 2, 1)
    ->flatMap(new class implements Func {
        public function apply($i) {
            return StreamBuilder::fromArray(array_fill(0, $i, $i));
        }
    })
    ->skip(1)
    ->forEach($debugConsumer);

separate();

echo "toArray\n";
$array = StreamBuilder::of(1, 2, 3)
    ->toArray();
var_dump($array);
