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

$phpExcel = new PHPExcel();

$generator = new SchemeGenerator(new PermutationLoader(__DIR__ . '/../data/permutation_files/'));

$players = [
    new Player('Rien'),
    new Player('Willem-jan'),
    new Player('Annette'),
    new Player('Bram')
];

$player = new Player('Adrie');
$generator->addSpecification(new CompositeOrSpecification(
    new AbsenceSpecification($player, new DateTime('2016-06-13'), new DateTime('2016-07-04')),
    new AbsenceSpecification($player, new DateTime('2016-08-29'), new DateTime('2016-09-12'))
));
$players[] = $player;

$player = new Player('Henk');
$generator->addSpecification(new AbsenceSpecification($player, new DateTime('2016-06-06'), new DateTime('2016-06-13')));
$players[] = $player;

$player = new Player('Erik');
$generator->addSpecification(new AbsenceSpecification($player, new DateTime('2016-04-28'), new DateTime('2016-05-11')));
$players[] = $player;

$player = new Player('Doss');
$generator->addSpecification(new CompositeOrSpecification(
    new AbsenceSpecification($player, new DateTime('2016-05-02'), new DateTime('2016-05-08')),
    new AbsenceSpecification($player, new DateTime('2016-07-18'), new DateTime('2016-08-07'))
));
$players[] = $player;

$player = new Player('Bart');
$generator->addSpecification(new AbsenceSpecification($player, new DateTime('2016-06-20'), new DateTime('2016-06-27')));
$players[] = $player;

$player = new Player('Luc');
$generator->addSpecification(new AbsenceSpecification($player, new DateTime('2016-09-10'), new DateTime('2016-09-25')));
$players[] = $player;

$player = new Player('Inge');
$generator->addSpecification(new CompositeOrSpecification(
    new AbsenceSpecification($player, new DateTime('2016-04-18'), new DateTime('2016-04-19')),
    new AbsenceSpecification($player, new DateTime('2016-05-09'), new DateTime('2016-05-16')),
    new AbsenceSpecification($player, new DateTime('2016-07-18'), new DateTime('2016-08-07'))
));
$players[] = $player;

$options = new SchemeGeneratorOptions(
    new DatePeriod(new DateTime( '2016-03-07' ), new DateInterval('P7D'), new DateTime( '2016-09-21' )),
    $players
);
$options->setMaxPlayersPerRound(8);
$options->setExcludeDates([new DateTime('2016-03-28'), new DateTime('2016-05-16')]);

$schemeData = $generator->generate($options);

$exporter = new ExcelExporter($phpExcel);
$exporter->export($schemeData, ['filename' => 'tennis' . uniqid() . '.xls']);

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
