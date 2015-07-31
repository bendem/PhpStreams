<?php

namespace streams;

use streams\impl\{
    SimpleCollector
};

final class Collectors {

    public static function of(Supplier $supplier, BiFunc $accumulator, Func $finisher = null) {
        return new SimpleCollector($supplier, $accumulator, $finisher);
    }

}
