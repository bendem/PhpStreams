<?php

namespace streams\impl;

use streams\Comparator;

class NaturalComparator implements Comparator {

    private static $INSTANCE = null;

    public function getInstance(): Comparator {
        if(static::$INSTANCE === null) {
            static::$INSTANCE = new NaturalComparator();
        }
        return static::$INSTANCE;
    }

    private function __construct() {}

    public function compare($o1, $o2): int {
        return $o1 <=> $o2;
    }

}
