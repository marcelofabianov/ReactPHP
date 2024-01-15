<?php

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;

require __DIR__.'/vendor/autoload.php';

function decrement(int $decrement, LoopInterface $loop): void
{
    $loop->addPeriodicTimer(1, function ($timer) use (&$decrement, &$loop) {
        if ($decrement < 0) {
            $loop->cancelTimer($timer);
            return;
        }

        echo $decrement . PHP_EOL;

        $decrement--;
    });
}

$loop = Loop::get();

decrement(5, $loop);

$loop->run();
