<?php

namespace streams\impl;

use streams\{
    BiFunc,
    Comparator
};

class EvictionBiFunc implements BiFunc {

    private $comparator;
    private $max;

    public function __construct(Comparator $comparator = null, bool $max) {
        $this->comparator = $comparator ?? NaturalComparator::getInstance();
        $this->max = $max;
    }

    public function apply($o1, $o2) {
        $res = $this->comparator->compare($o1, $o2);
        if($this->max && $res > 0 || !$this->max && $res < 0) {
            return $o1;
        }
        return $o2;
    }

}
