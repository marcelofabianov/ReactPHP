<?php

use React\EventLoop\Loop;
use React\Socket\Connector;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer;

require __DIR__ . '/vendor/autoload.php';

$loop = Loop::get();

$socket = new SocketServer('127.0.0.1:8080', [], $loop);

$socket->on('connection', function (ConnectionInterface $connection) {
    $connection->write('Welcome to this amazing server!' . PHP_EOL);

    $connection->on('data', function ($data) use ($connection) {
        $connection->write($data);
    });

    $connection->on('close', function () {
        echo "Connection closed\n";
    });
});
