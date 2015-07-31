<?php

declare(strict_types = 1);

require '../vendor/autoload.php';

header('Content-type: text/plain');

$chrono = new class {
    private $start;

    public function start($title) {
        echo "$title\n";
        $this->start = microtime(true);
    }

    public function stop() {
        echo number_format(microtime(true) - $this->start, 10) . "µs\n\n";
    }
};
