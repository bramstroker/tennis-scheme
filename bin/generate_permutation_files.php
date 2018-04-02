<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 10:23
 */

require __DIR__ . '/../vendor/autoload.php';

ini_set('memory_limit', '2G');

$numPlayers = 8;
$numSpots = 8;
$folder = __DIR__ . '/../data/permutation_files/';
$filename = 'permutations_' . $numPlayers . '_' . $numSpots . '.txt';

$sourceDataSet = range(0, $numPlayers - 1);

$permutations = new \drupol\phpermutations\Generators\Permutations($sourceDataSet, $numSpots);

$data = '';
$i = 0;
$lines = [];
foreach ($permutations->generator() as $permutation) {
    $data .= join('-', $permutation) . "\n";
    $i++;
    if ($i % 100000 == 1) {
        echo $i . PHP_EOL;
        echo memory_get_usage() . PHP_EOL;
        file_put_contents($folder . $filename, $data, FILE_APPEND);
        $data = '';
    }
}

//flush remainder
file_put_contents($folder . $filename, $data, FILE_APPEND);