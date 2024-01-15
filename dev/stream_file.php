<?php

use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use React\Stream\ReadableResourceStream;
use React\Stream\ReadableStreamInterface;

require __DIR__.'/vendor/autoload.php';

$loop = Loop::get();
$stream = new ReadableResourceStream(fopen('example.txt', 'rb'), $loop);

$manager = new class ($stream, $loop) {
    const int LINES_PER_CHUNK = 11;

    const int MAX_CHUNKS = 5;

    private string $buffer = '';

    private int $countLines = 0;

    public function __construct(
        private readonly ReadableStreamInterface $stream,
        private readonly LoopInterface $loop
    ) {
    }

    public function run(): void
    {
        $this->start();
        $this->end();
        $this->error();

        $this->loop->run();
    }

    public function start(): void
    {
        $stream = $this->stream;
        $buffer = &$this->buffer;
        $countLines = &$this->countLines;

        $stream->on('data', function ($data) use (&$buffer, &$countLines, $stream) {
            $buffer .= $data;
            $lines = explode("\n", $buffer);

            while(count($lines) >= self::LINES_PER_CHUNK) {
                $chunk = implode("\n", array_splice($lines, 0, self::LINES_PER_CHUNK));

                echo PHP_EOL."Chunk: $chunk".PHP_EOL;

                $countLines += self::LINES_PER_CHUNK;

                if ($countLines >= self::MAX_CHUNKS * self::LINES_PER_CHUNK) {
                    $stream->close();
                    break;
                }
            }

            $buffer = implode("\n", $lines);
        });
    }

    public function end(): void
    {
        $this->stream->on('end', function () use (&$buffer, &$countLines) {
            if ($buffer !== '') {
                $countLines += substr_count($buffer, "\n");
                echo "Remaining: $buffer\n";
            }

            echo "Total lines: $countLines\n";
        });
    }

    public function error(): void
    {
        $this->stream->on('error', function (Exception $e) {
            echo "Error: {$e->getMessage()}\n";
        });
    }
};

if (!file_exists('example.txt')) {
    echo 'Error: example.txt does not exist. Please run "php generate.php" first.' . PHP_EOL;
}

$manager->run();

/**
 * # php generate.php
 * # php stream_file.php
 */
