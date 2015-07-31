<?php

namespace streams;

interface Collector {

    function supplier(): Supplier;
    function accumulator(): BiFunc;
    function finisher(): Func;

}
