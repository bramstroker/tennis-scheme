<?php
/**
 * Created by PhpStorm.
 * User: bram
 * Date: 12/16/15
 * Time: 6:06 PM
 */

namespace StrokerTennis\SchemeGenerator;

use DatePeriod;
use DateTime;
use PHPExcel;
use PHPExcel_IOFactory;
use StrokerTennis\Model\Match;
use StrokerTennis\Model\Team;
use StrokerTennis\Permutation\PermutationLoader;
use StrokerTennis\SchemeGenerator\Exception\NotEnoughPlayersException;
use StrokerTennis\Specification\SpecificationInterface;

class SchemeGenerator implements SchemeGeneratorInterface
{
    /** @var PermutationLoader */
    protected $permutationLoader;

    /** @var SpecificationInterface[] */
    protected $specifications = [];

    /** @var array */
    protected $totalCountPerPlayer = [];

    /**
     * @param PermutationLoader $permutationLoader
     */
    public function __construct(PermutationLoader $permutationLoader)
    {
        $this->permutationLoader = $permutationLoader;
    }

    /**
     * @param SpecificationInterface $specification
     */
    public function addSpecification(SpecificationInterface $specification)
    {
        $this->specifications[] = $specification;
    }
    
    /**
     * @param SchemeGeneratorOptions $options
     * @return SchemeData
     */
    public function generate(SchemeGeneratorOptions $options)
    {
        // Reset counts for all players
        $this->totalCountPerPlayer = array_fill(0, count($options->getPlayers()), 0);

        return $this->doIteration($options);
    }

    /**
     * @param SchemeGeneratorOptions $options
     * @param int $iteration
     * @return SchemeData
     */
    protected function doIteration(SchemeGeneratorOptions $options, $iteration = 0)
    {
        $iterationCountPerPlayer = array_fill(0, count($options->getPlayers()), 0);
        $players = $options->getPlayers();
        $permutations = $this->permutationLoader->getPermutations(count($players));
        $schemeData = new SchemeData();
        foreach($options->getDatePeriod() as $round => $date) {

            if (in_array($date, $options->getExcludeDates())) {
                continue;
            }

            $playerIndices = $this->selectPlayersForThisRound($options, $date);

            $count = 0;
            $match = new Match();
            $team = new Team();
            foreach ($permutations[$round] as $playerIndex) {
                if (!isset($playerIndices[$playerIndex])) {
                    continue;
                }

                if ($count % 4 == 0) {
                    $match = new Match();
                    $match->setRound($round);
                    $match->setDateTime($date);
                    $schemeData->addMatch($match);
                }
                if ($count % 2 == 0) {
                    $team = new Team();
                    $match->addTeam($team);
                }

                $team->addPlayer($players[$playerIndex]);

                $count++;

                $iterationCountPerPlayer[$playerIndex]++;
                $this->totalCountPerPlayer[$playerIndex]++;
            }
        }

        // Prevent too much resursion
        if ($iteration > 10) {
            return $schemeData;
        }

        if ((max($iterationCountPerPlayer) - min($iterationCountPerPlayer)) > 2) {
            return $this->doIteration($options, $iteration + 1);
        }

        return $schemeData;
    }

    /**
     * Select players which needs to play this round.
     * 
     * @param SchemeGeneratorOptions $options
     * @param DateTime $date
     * @return array
     * @throws NotEnoughPlayersException
     */
    protected function selectPlayersForThisRound(SchemeGeneratorOptions $options, DateTime $date)
    {
        $playersForThisRound = [];
        $players = $options->getPlayers();
        asort($this->totalCountPerPlayer);
        foreach ($this->totalCountPerPlayer as $playerIndex => $count) {
            foreach ($this->specifications as $spec) {
                if ($spec->isSatisfiedBy($players[$playerIndex], $date)) {
                    continue 2;
                }
            }
            $playersForThisRound[$playerIndex] = $playerIndex;
            if (count($playersForThisRound) == $options->getMaxPlayersPerRound()) {
                break;
            }
        }

        if (count($playersForThisRound) < $options->getMaxPlayersPerRound()) {
            throw new NotEnoughPlayersException(sprintf('Too few players on date %s', $date->format('d-m-Y')));
        }
        return $playersForThisRound;
    }
} 