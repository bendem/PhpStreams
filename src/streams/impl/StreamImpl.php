<?php

namespace streams\impl;

use Generator;
use Iterator;
use Traversable;
use streams\{
    BiFunc,
    Comparator,
    Consumer,
    Func,
    Optional,
    Predicate,
    Stream
};

class StreamImpl implements Stream {

    private $pipeline;
    private $targets = [];

    public function __construct(Traversable $target) {
        $this->pipeline = new Pipeline();
        $this->targets[] = $target;
    }

    public function getIterator(): Iterator {
        return $this->pipeline->execute(...$this->targets);
    }

    public function concat(Traversable $target): Stream {
        $this->targets[] = $target;
        return $this;
    }

    public function filter(Predicate $predicate): Stream {
        $this->pipeline->addOperation(OperationType::FILTER, $predicate);
        return $this;
    }

    public function distinct(): Stream {
        return $this->filter(new DistinctPredicate());
    }

    public function sort(Comparator $comparator = null): Stream {
        $this->pipeline->addOperation(OperationType::SORT, $comparator ?? NaturalComparator::INSTANCE);
    }

    public function map(Func $function): Stream {
        $this->pipeline->addOperation(OperationType::MAP, $function);
        return $this;
    }

    public function flatMap(Traversable $function): Stream {
        // TODO: Implement flatMap() method.
    }

    public function findFirst(): Optional {
        // TODO: Implement findFirst() method.
    }

    public function findAny(): Optional {
        // TODO: Implement findAny() method.
    }

    public function allMatch(Predicate $predicate): bool {
        // TODO: Implement allMatch() method.
    }

    public function anyMatch(Predicate $predicate): bool {
        // TODO: Implement anyMatch() method.
    }

    //function collect(Closure $supplier, Closure $accumulator): Stream {
    //     TODO: Implement collect() method.
    //}

    public function limit(int $size): Stream {
        $this->pipeline->setLimit($size);
        return $this;
    }

    public function skip(int $count): Stream {
        $this->pipeline->addOperation(OperationType::SKIP, $count);
        return $this;
    }

    public function peek(Consumer $consumer): Stream {
        $this->pipeline->addOperation(OperationType::PEEK, $consumer);
        return $this;
    }

    public function min(Comparator $comparator): Optional {
        // TODO: Implement min() method.
    }

    public function max(Comparator $comparator): Optional {
        // TODO: Implement max() method.
    }

    public function noneMatch(Predicate $predicate): bool {
        // TODO: Implement noneMatch() method.
    }

    public function reduce($identity, BiFunc $biFunc) {
        foreach($this->pipeline->execute(...$this->targets) as $v) {
            $identity = $biFunc->apply($identity, $v);
        }
        return $identity;
    }

    public function count(): int {
        $i = 0;
        foreach($this->pipeline->execute(...$this->targets) as $_) {
            ++$i;
        }
        return $i;
    }

    public function forEach(Consumer $consumer) {
        foreach($this->pipeline->execute(...$this->targets) as $v) {
            $consumer->accept($v);
        }
    }

}
