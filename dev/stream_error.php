<?php

use React\EventLoop\Loop;
use React\Stream\ReadableResourceStream;
use React\Stream\ThroughStream;
use React\Stream\WritableResourceStream;

require __DIR__ . '/vendor/autoload.php';

function error(string $message): void
{
    dd($message);
}

$loop = Loop::get();

$readable = new ReadableResourceStream(STDIN, $loop);
$writable = new WritableResourceStream(STDOUT, $loop);
$error = new WritableResourceStream(STDERR, $loop);

$toUpper = new ThroughStream(fn ($chunk) => strtoupper($chunk));
$toStringReverse = new ThroughStream(fn ($chunk) => strrev($chunk));
$toSnakeCase = new ThroughStream(fn ($chunk) => str_replace(' ', '_', strtolower($chunk)));
$exampleError = new ThroughStream(fn ($chunk) => throw new Exception('Example error'));

$exampleError->on('error', fn (Exception $error) => error($error->getMessage()));

$readable
    ->pipe($toUpper)
    ->pipe($toStringReverse)
    ->pipe($toSnakeCase)
    ->pipe($exampleError)
    ->pipe($writable);

$loop->run();

/**
 * echo "hello World" | php stream.php
 */
