<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace StrokerTennis\Factory;


use DateInterval;
use DatePeriod;
use DateTime;
use StrokerTennis\Model\Player;
use StrokerTennis\SchemeGenerator\SchemeGeneratorOptions;
use StrokerTennis\Specification\AbsenceSpecification;
use StrokerTennis\Specification\CompositeOrSpecification;

class SchemeGeneratorOptionsFactory
{
    /**
     * @param string $json
     * @return SchemeGeneratorOptions
     * @throws \Exception
     */
    public static function createOptionsFromJson(string $json): SchemeGeneratorOptions
    {
        $definition = json_decode($json, true);

        $options = new SchemeGeneratorOptions(
            new DatePeriod(
                new DateTime($definition['start']),
                new DateInterval('P7D'),
                new DateTime($definition['end'])
            )
        );

        foreach ($definition['players'] as $playerDef) {
            $player = new Player($playerDef['name']);
            $options->addPlayer($player);
            if (isset($playerDef['absence'])) {
                self::setAbsence($options, $player, $playerDef['absence']);
            }
        }

        $options->setMaxPlayersPerRound($definition['maxPlayersPerRound'] ?? 8);

        if (isset($definition['excludeDates'])) {
            $excludeDates = array_map(function($date) { return new DateTime($date); }, $definition['excludeDates']);
            $options->setExcludeDates($excludeDates);
        }

        return $options;
    }

    /**
     * @param SchemeGeneratorOptions $options
     * @param array $absenceDefinition
     */
    private static function setAbsence(SchemeGeneratorOptions $options, Player $player, array $absenceDefinition): void
    {
        $specs = [];
        foreach ($absenceDefinition as $absence) {
            if (\is_array($absence)) {
                $specs[] = new AbsenceSpecification($player, new DateTime($absence[0]), new DateTime($absence[1]));
            } else {
                $specs[] = new AbsenceSpecification($player, new DateTime($absence));
            }
        }
        $options->addSpecification(new CompositeOrSpecification(... $specs));
    }
}