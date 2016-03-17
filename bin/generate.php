<?php
/**
 * Created by PhpStorm.
 * User: bram
 * Date: 12/16/15
 * Time: 6:07 PM
 */

error_reporting(E_ALL);

//ini_set('memory_limit', '4G');

use StrokerTennis\Permutation\PermutationLoader;
use StrokerTennis\SchemeGenerator;
use StrokerTennis\ValueObject\Player;

include ('../vendor/autoload.php');

$phpExcel = new PHPExcel();
$generator = new SchemeGenerator($phpExcel, new PermutationLoader(__DIR__ . '/../data/permutation_files/'));
/*$generator->setPlayers(['bram', 'luc', 'adrie', 'rien', 'willem-jan', 'annette', 'eef', 'erik', 'inge', 'henk', 'doss', 'bar']);
$generator->setPlayers(['bram', 'luc', 'adrie', 'rien', 'willem-jan', 'annette', 'eef', 'erik', 'inge', 'henk', 'doss']);
$generator->setPlayers(['bram', 'luc', 'adrie', 'rien', 'willem-jan', 'annette', 'eef', 'erik', 'inge', 'henk']);
$generator->setPlayers(['bram', 'luc', 'adrie', 'rien', 'willem-jan', 'annette', 'eef', 'erik', 'inge']);*/
$players = [
    new Player('bram'),
    new Player('luc'),
    new Player('adrie'),
    new Player('rien'),
    new Player('willem-jan'),
    new Player('annette'),
    new Player('eef'),
    new Player('erik'),
    new Player('inge'),
    new Player('henk'),
    new Player('doss'),
];

$options = new \StrokerTennis\SchemeGeneratorOptions(
    new DatePeriod(new DateTime( '2012-08-01' ), new DateInterval('P7D'), new DateTime( '2012-11-01' )),
    $players
);
$options->setMaxPlayersPerRound(8);

//$generator->setPlayers(['bram', 'luc', 'adrie', 'rien', 'willem-jan', 'annette', 'eef', 'erik', 'inge', 'henk']);;
$generator->generate('tennis.xls', $options);




/*
 * $permutations = [];
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

        //CombinationUtils::computePermutations($this->players, $permutations);
        //CombinationUtils::permuteUnique($this->players, [], $permutations);
        //permutations = CombinationUtils::permuteUnique2($this->players);



        echo 'mem usage: ' . memory_get_usage() . PHP_EOL;
        echo count($permutations) . ' permutations' . PHP_EOL;
        //echo microtime(true) - $start . ' elapsed';
        exit;
        //shuffle($permutations);
 */




