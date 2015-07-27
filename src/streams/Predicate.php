<?php

namespace streams;

interface Predicate {

    function test($object): bool;

}
