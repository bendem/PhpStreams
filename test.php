<?php

header('Content-type: text/plain');

function gen(int $count) {
    $i = 0;
    while($i < $count) {
        yield $i++;
    }
}

$gen = gen(5);

while($gen->valid()) {
    var_dump($gen->current());
    $gen->next();
}
