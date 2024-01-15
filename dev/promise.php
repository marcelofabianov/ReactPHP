<?php

use React\Promise\Promise;

require __DIR__ . '/vendor/autoload.php';

if (!file_exists('example.txt')) {
    echo 'Error: example.txt does not exist. Please run "php generate.php" first.' . PHP_EOL;
}

function getFileAsync(): Promise
{
    return new Promise(
        static function (callable $resolve, callable $reject) {
            try {
                $resolve(file_get_contents('example.txt'));
            } catch (Throwable $e) {
                $reject($e);
            }
        }
    );
}

getFileAsync()
    ->then(fn() => print('Resolved') . PHP_EOL)
    ->catch(fn() => print('Rejected') . PHP_EOL);
