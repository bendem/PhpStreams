<?php

namespace streams\impl;

use Exception;
use Traversable;

class Pipeline {

    private $operations = [];
    private $limit = PHP_INT_MAX;
    private $skipping = 0;
    private $expired = false;

    public function __construct() {}

    public function addOperation(/*OperationType*/int $type, $operation) {
        $this->operations[] = [$type, $operation];
    }

    public function setLimit(int $count) {
        if($this->expired) {
            throw new Exception('Pipeline has already been executed');
        }
        $this->limit = $count;
    }

    public function execute(Traversable... $targets) {
        if($this->expired) {
            throw new Exception('Pipeline has already been executed');
        }
        $this->expired = true;

        $i = 0;

        foreach($targets as $target) {
            foreach($target as $v) {
                if($i >= $this->limit) {
                    break 2;
                }

                if($this->skipping > 0) {
                    --$this->skipping;
                    continue;
                }

                list($value, $filtered) = $this->applyOperations($v);

                if(!$filtered) {
                    ++$i;
                    yield $value;
                }
            }
        }
    }

    private function applyOperations($value) {
        foreach($this->operations as &$operation) {
            list($value, $filtered) = $this->applyOperation($operation[0], $operation[1], $value);

            if($filtered) {
                return [null, true];
            }
        }
        return [$value, false];
    }

    private function applyOperation(/*OperationType*/int $type, &$operation, $value) {
        switch($type) {
            case OperationType::FILTER: {
                if(!$operation->test($value)) {
                    return [null, true];
                }
                break;
            }
            case OperationType::MAP: {
                $value = $operation->apply($value);
                break;
            }
            case OperationType::FLAT_MAP: {
                // TODO
                break;
            }
            case OperationType::SORT: {
                // TODO
                break;
            }
            case OperationType::SKIP: {
                if($operation > 0) {
                    $this->skipping += $operation - 1;
                    // Skipping only happens once, not for each value
                    // handled by the pipeline
                    $operation = 0;
                    return [null, true];
                }
                break;
            }
            case OperationType::PEEK: {
                $operation->accept($value);
                break;
            }
            default: {
                throw new Exception('Unsuported operation in the pipeline: ' . $type);
            }
        }
        return [$value, false];
    }

}
