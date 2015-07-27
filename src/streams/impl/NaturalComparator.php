<?php

namespace streams\impl;

use streams\Comparator;

class NaturalComparator implements Comparator {

    const INSTANCE = new NaturalComparator();

    private function compare($o1, $o2): int {
        return $o1 <=> $o2;
    }

}
