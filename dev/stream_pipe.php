<?php

use React\EventLoop\Loop;
use React\Stream\ReadableResourceStream;
use React\Stream\ThroughStream;
use React\Stream\WritableResourceStream;

require __DIR__ . '/vendor/autoload.php';

$loop = Loop::get();

$readable = new ReadableResourceStream(STDIN, $loop);
$writable = new WritableResourceStream(STDOUT, $loop);

$toUpper = new ThroughStream(fn ($chunk) => strtoupper($chunk));
$toStringReverse = new ThroughStream(fn ($chunk) => strrev($chunk));
$toSnakeCase = new ThroughStream(fn ($chunk) => str_replace(' ', '_', strtolower($chunk)));

$readable
    ->pipe($toUpper)
    ->pipe($toStringReverse)
    ->pipe($toSnakeCase)
    ->pipe($writable);

$loop->run();

/**
 * echo "hello World" | php stream.php
 */
