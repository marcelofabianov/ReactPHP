<?php

use React\Http\Browser;
use function React\Async\async;
use function React\Async\await;

require __DIR__ . '/vendor/autoload.php';

$promise = async(function () {
    $user = 'marcelofabianov';
    $browser = new Browser();

    $promise = $browser->get("https://api.github.com/users/$user/repos");

    try {
        $response = await($promise);
    } catch (Exception $exception) {
        $promise->cancel();
        throw $exception;
    }

    return $response;
})();

$promise->then(function ($response) {
    $data = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

    foreach ($data as $repo) {
        echo $repo->full_name . PHP_EOL;
    }
}, function (Exception $exception) {
    dd($exception->getMessage());
});
