<?php
/**
 * Created by PhpStorm.
 * User: bram
 * Date: 12/16/15
 * Time: 6:07 PM
 */

use StrokerTennis\Model\Player;
use StrokerTennis\Permutation\PermutationLoader;
use StrokerTennis\SchemeExporter\ExcelExporter;
use StrokerTennis\SchemeGenerator\SchemeGenerator;
use StrokerTennis\SchemeGenerator\SchemeGeneratorOptions;;
use StrokerTennis\Specification\AbsenceSpecification;
use StrokerTennis\Specification\CompositeOrSpecification;

include ('../vendor/autoload.php');

ini_set('memory_limit', '2G');

$phpExcel = new PHPExcel();

$logger = new \Monolog\Logger('log', [new \Monolog\Handler\StreamHandler('php://stdout')]);

$generator = new SchemeGenerator(new PermutationLoader(__DIR__ . '/../data/permutation_files/'), $logger);

$players = [
    new Player('Rien'),
    new Player('Willem-jan'),
    new Player('Annette'),
    new Player('Bram'),
    new Player('Adrie'),
    new Player('Henk'),
    new Player('Erik'),
    new Player('Doss'),
    new Player('Bart'),
    new Player('Luc'),
    new Player('Pietje')
];

$player = new Player('Inge');
$generator->addSpecification(new CompositeOrSpecification(
    new AbsenceSpecification($player, new DateTime('2018-04-09')),
    new AbsenceSpecification($player, new DateTime('2018-04-23')),
    new AbsenceSpecification($player, new DateTime('2016-05-07'), new DateTime('2016-05-21'))
));
$players[] = $player;

$options = new SchemeGeneratorOptions(
    new DatePeriod(new DateTime( '2018-04-09' ), new DateInterval('P7D'), new DateTime( '2018-09-30' )),
    $players
);
$options->setMaxPlayersPerRound(8);
$options->setExcludeDates([new DateTime('2018-05-21')]);

$schemeData = $generator->generate($options);

$exporter = new ExcelExporter($phpExcel);
$exporter->export($schemeData, ['filename' => __DIR__ . '/../export/tennis' . uniqid() . '.xls']);

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