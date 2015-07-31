<?php

namespace streams\impl;

use streams\{
    BiFunc,
    Collector,
    Func,
    Supplier
};

class SimpleCollector implements Collector {

    private $supplier;
    private $accumulator;
    private $finisher;

    public function __construct(Supplier $supplier, BiFunc $accumulator, Func $finisher = null) {
        $this->supplier = $supplier;
        $this->accumulator = $accumulator;
        $this->finisher = $finisher;
    }

    public function supplier(): Supplier {
        return $this->supplier;
    }

    public function accumulator(): Accumulator {
        return $this->accumulator;
    }

    public function finisher(): Finisher {
        return $this->finisher;
    }

}
