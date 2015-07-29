<?php

declare(strict_types = 1);

namespace streams\impl;

use streams\Predicate;

class DistinctPredicate implements Predicate {

    private $booleans = [];
    private $scalars = [];
    private $objects = []; // TODO Use a more appropriate data structure?

    public function test($obj): bool {
        if(is_bool($obj)) {
            return $this->handleBoolean($obj);
        }
        if(is_scalar($obj)) {
            return $this->handleScalar($obj);
        }
        return $this->handleObject($obj);
    }

    private function handleBoolean($bool) {
        if(in_array($bool, $this->booleans)) {
            return false;
        }
        $this->booleans[] = $bool;
        return true;
    }

    private function handleScalar($scalar) {
        if(isset($this->scalars[$scalar])) {
            return false;
        }
        $this->scalars[$scalar] = true;
        return true;
    }

    private function handleObject($obj) {
        $hash = spl_object_hash($obj);
        if(isset($this->objects[$hash])) {
            return false;
        }
        $this->objects[$hash] = true;
        return true;
    }

}
