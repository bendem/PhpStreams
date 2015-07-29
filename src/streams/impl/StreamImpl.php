<?php

declare(strict_types = 1);

namespace streams\impl;

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

    /** @var Pipeline The execution pipeline */
    private $pipeline;

    /** @var array Array of Traversables to execute the pipeline on */
    private $targets = [];

    public function __construct(Traversable $target) {
        $this->pipeline = new Pipeline();
        $this->targets[] = $target;
    }

    public function getIterator(): Iterator {
        return $this->execute();
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

    public function sorted(Comparator $comparator = null): Stream {
        $this->pipeline->addOperation(OperationType::SORT, $comparator ?? NaturalComparator::getInstance());
        return $this;
    }

    public function map(Func $function): Stream {
        $this->pipeline->addOperation(OperationType::MAP, $function);
        return $this;
    }

    public function flatMap(Func $function): Stream {
        $this->pipeline->addOperation(OperationType::FLAT_MAP, $function);
        return $this;
    }

    public function findFirst(): Optional {
        $gen = $this->execute();
        if($gen->valid()) {
            return Optional::of($gen->current());
        } else {
            return Optional::empty();
        }
    }

    public function findAny(): Optional {
        $this->pipeline->setPreventSorting();
        return $this->findFirst();
    }

    public function allMatch(Predicate $predicate): bool {
        $gen = $this->execute();

        while($gen->valid()) {
            if(!$predicate->test($gen->current())) {
                return false;
            }
            $gen->next();
        }

        return true;
    }

    public function anyMatch(Predicate $predicate): bool {
        $gen = $this->execute();

        while($gen->valid()) {
            if($predicate->test($gen->current())) {
                return true;
            }
            $gen->next();
        }

        return false;
    }

    public function noneMatch(Predicate $predicate): bool {
        $gen = $this->execute();

        while($gen->valid()) {
            if($predicate->test($gen->current())) {
                return false;
            }
            $gen->next();
        }

        return true;
    }

    //public function collect(Closure $supplier, Closure $accumulator): Stream {
    //     TODO: Implement collect() method.
    //}

    public function limit(int $size): Stream {
        $this->pipeline->addOperation(OperationType::LIMIT, $size);
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

    public function min(Comparator $comparator = null): Optional {
        return $this->reduce(new EvictionBiFunc($comparator, false));
    }

    public function max(Comparator $comparator = null): Optional {
        return $this->reduce(new EvictionBiFunc($comparator, true));
    }

    public function reduce(BiFunc $accumulator): Optional {
        $first = true;
        foreach($this->execute() as $v) {
            if($first) {
                $identity = $v;
                $first = false;
            } else {
                $identity = $accumulator->apply($identity, $v);
            }
        }

        if($first) {
            return Optional::empty();
        } else {
            return Optional::of($identity);
        }
    }

    public function count(): int {
        $i = 0;
        $gen = $this->execute();

        while($gen->valid()) {
            $gen->next();
            ++$i;
        }

        return $i;
    }

    public function forEach(Consumer $consumer) {
        $gen = $this->execute();
        while($gen->valid()) {
            $consumer->accept($gen->current());
            $gen->next();
        }
    }

    private function execute(): Iterator {
        return $this->pipeline->execute(...$this->targets);
    }

}
