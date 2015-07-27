<?php

namespace streams;

interface Comparator {

    function compare($o1, $o2): int;

}
