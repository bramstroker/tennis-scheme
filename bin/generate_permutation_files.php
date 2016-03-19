<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 10:23
 */

use NajiDev\Permutation\PermutationIterator;

$permutations = [];
$permutationIterator = new PermutationIterator(range(0, 8));
$permutations = [];
$i = 0;
$start = microtime(true);

foreach ($permutationIterator as $permutation) {
    file_put_contents('permutations.txt', join('-', $permutation) . "\n", FILE_APPEND);
    $i++;
    if ($i % 10000 == 1) {
        echo $i . PHP_EOL;
    }
}

echo 'mem usage: ' . memory_get_usage() . PHP_EOL;
echo count($permutations) . ' permutations' . PHP_EOL;

