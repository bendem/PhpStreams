<?php

namespace streams\impl;

use streams\Predicate;

class DistinctPredicate implements Predicate {

    private $objects = []; // TODO Use a more appropriate data structure?

    public function test($obj): bool {
        foreach($this->objects as $v) {
            if($obj === $v) {
                return false;
            }
        }
        $this->objects[] = $obj;
        return true;
    }

}
