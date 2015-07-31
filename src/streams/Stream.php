<?php

declare(strict_types = 1);

namespace streams;

use IteratorAggregate;
use Traversable;

interface Stream extends IteratorAggregate {

    function concat(Traversable $target): Stream;
    function distinct(): Stream;
    function filter(Predicate $predicate): Stream;
    function flatMap(Func $function): Stream;
    function limit(int $size): Stream;
    function map(Func $function): Stream;
    function peek(Consumer $consumer): Stream;
    function skip(int $count): Stream;
    function sorted(Comparator $comparator = null): Stream;

    function max(Comparator $comparator = null): Optional;
    function min(Comparator $comparator = null): Optional;

    function findAny(): Optional;
    function findFirst(): Optional;

    function allMatch(Predicate $predicate): bool;
    function anyMatch(Predicate $predicate): bool;
    function noneMatch(Predicate $predicate): bool;

    // TODO Reduce with provided indentity (reduce($identity, BiFunc $accumulator))
    function reduce(BiFunc $accumulator);
    function collect(Collector $collector);

    function toArray(): array;

    function forEach(Consumer $consumer);

    function count(): int;

}
