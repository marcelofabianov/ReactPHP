<?php

require __DIR__.'/../vendor/autoload.php';

$loop = \React\EventLoop\Loop::get();


echo 'start' . PHP_EOL;

$loop->addTimer(1, function () {
    echo "1\n";
});

$loop->addTimer(2, function () {
    echo "2\n";
});

$loop->addTimer(3, function () {
    echo "3\n";
});

$loop->run();

echo 'end' . PHP_EOL;
