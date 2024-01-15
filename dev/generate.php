<?php

$numbers = 100000;
$arquivo = fopen("example.txt", 'wb');

for ($i = 1; $i <= $numbers; $i++) {
    if ($i % 10 === 1) {
        fwrite($arquivo, " \n");
    }
    $linha = $i . " - Lorem ipsum dolor sit amet. Cum aliquid fugiat et nisi voluptatem non distinctio libero ab error nihil et voluptates magnam.\n";
    fwrite($arquivo, $linha);
}

fclose($arquivo);
