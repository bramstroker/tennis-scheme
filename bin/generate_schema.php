<?php
/**
 * Created by PhpStorm.
 * User: bram
 * Date: 12/16/15
 * Time: 6:07 PM
 */

use Monolog\Logger;
use StrokerTennis\Factory\SchemeGeneratorOptionsFactory;
use StrokerTennis\Permutation\PermutationLoader;
use StrokerTennis\SchemeExporter\ExcelExporter;
use StrokerTennis\SchemeGenerator\SchemeGenerator;

include ('../vendor/autoload.php');

ini_set('memory_limit', '2G');

$logger = new Logger('log', [new \Monolog\Handler\StreamHandler('php://stdout')]);

$generator = new SchemeGenerator(new PermutationLoader(__DIR__ . '/../data/permutation_files/'), $logger);

$options = SchemeGeneratorOptionsFactory::createOptionsFromJson(file_get_contents(__DIR__ . '/../definition/2021_najaar.json'));

$schemeData = $generator->generate($options);

$exporter = new ExcelExporter();
$exporter->export($schemeData, ['filename' => __DIR__ . '/../export/tennis' . uniqid() . '.xlsx']);

$playerCounts = [];
foreach ($schemeData->getRounds() as $round) {
    foreach ($schemeData->getPlayersForRound($round) as $player) {
        if (!isset($playerCounts[$player->getName()])) {
            $playerCounts[$player->getName()] = 0;
        }
        $playerCounts[$player->getName()]++;
    }
}
foreach ($playerCounts as $name => $count) {
    printf("%s: %d\n", $name, $count);
}

$matchesPerTeam = $schemeData->getNumberOfMatchesPerTeam();
ksort($matchesPerTeam);
foreach ($matchesPerTeam as $team => $count) {
    printf("%s: %d\n", $team, $count);
}