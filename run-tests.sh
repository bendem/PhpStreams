#!/bin/sh
if [ ! -f "run-tests.php" ]; then
    wget -q https://raw.githubusercontent.com/php/php-src/master/run-tests.php
fi

PHP_BINARY=${PHP_BINARY:-php}
$PHP_BINARY "run-tests.php" -P -q tests/*.phpt
