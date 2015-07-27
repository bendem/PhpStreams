<?php

namespace streams;

class Optional {

    private $value;
    private $hasValue;

    private function __construct() {
        if(func_num_args() == 0) {
            $this->hasValue = false;
        } else {
            $this->value = func_get_arg(0);
            $this->hasValue = true;
        }
    }

    public static function empty(): Optional {
        return new Optional();
    }

    public static function of($arg): Optional {
        return new Optional($arg);
    }

    public function get() {
        if(!$this->hasValue) {
            // TODO Better exception hierarchy
            throw new \Exception();
        }
        return $this->value;
    }

    public function isPresent(): bool {
        return $this->value !== null;
    }

}
