<?php

use React\EventLoop\Loop;
use React\Socket\Connector;
use React\Socket\ConnectionInterface;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;

require __DIR__ . '/vendor/autoload.php';

$loop = Loop::get();

$connector = new Connector($loop);
$input = new ReadableResourceStream(STDIN, $loop);
$output = new WritableResourceStream(STDOUT, $loop);

$connector->connect('127.0.0.1:8080')
    ->then(
        function (ConnectionInterface $connection) use ($input, $output) {
            $input->pipe($connection)->pipe($output);
        },
        function (Exception $exception) {
            echo "Cannot connect to server: {$exception->getMessage()}\n";
        }
    );

$loop->run();
