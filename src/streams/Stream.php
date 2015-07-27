<?php

declare(strict_types = 1);

namespace streams;

use IteratorAggregate;
use Traversable;

interface Stream extends IteratorAggregate {

    function concat(Traversable $target): Stream;
    function distinct(): Stream;
    function filter(Predicate $predicate): Stream;
    function flatMap(Traversable $function): Stream;
    function limit(int $size): Stream;
    function map(Func $function): Stream;
    function peek(Consumer $consumer): Stream;
    function skip(int $count): Stream;
    function sort(Comparator $comparator = null): Stream;

    function max(Comparator $comparator): Optional;
    function min(Comparator $comparator): Optional;

    function findAny(): Optional;
    function findFirst(): Optional;

    function allMatch(Predicate $predicate): bool;
    function anyMatch(Predicate $predicate): bool;
    function noneMatch(Predicate $predicate): bool;

    function reduce($identity, BiFunc $biFunc);

    // TODO
    // function collect(Supplier $supplier,  $accumulator);

    function forEach(Consumer $consumer);

    function count(): int;

}
