<?php
/**
 * Created by PhpStorm.
 * User: bram
 * Date: 12/16/15
 * Time: 6:06 PM
 */

namespace StrokerTennis\SchemeGenerator;

use DateTime;
use Psr\Log\LoggerInterface;
use StrokerTennis\Model\Match;
use StrokerTennis\Model\Player;
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

    /** @var array */
    protected $iterationCountPerPlayer = [];

    /** @var array */
    private $permutations = [];

    /** @var Player[] */
    private $players = [];

    /** @var Team[] */
    private $teams = [];

    /**
     * @var SchemeData
     */
    private $schemeData;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param PermutationLoader $permutationLoader
     * @param LoggerInterface $logger
     */
    public function __construct(PermutationLoader $permutationLoader, LoggerInterface $logger)
    {
        $this->permutationLoader = $permutationLoader;
        $this->logger = $logger;
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
        $this->players = $options->getPlayers();

        $this->schemeData = new SchemeData();
        $this->schemeData->setTeams($this->createTeams());

        $this->iterationCountPerPlayer = array_fill(0, count($this->players), 0);

        $this->permutations = $this->permutationLoader->getPermutations(count($this->players), $options->getMaxPlayersPerRound());

        foreach($options->getDatePeriod() as $round => $date) {

            if (in_array($date, $options->getExcludeDates())) {
                continue;
            }

            $this->selectMatchesForRound($round, $options, $date);

            $this->logger->info('Generating round ' . $round);
        }

        if ($iteration < 8 && (max($this->iterationCountPerPlayer) - min($this->iterationCountPerPlayer)) > 2) {
            return $this->doIteration($options, ++$iteration);
        }

        return $this->schemeData;
    }

    /**
     * @param int $round
     * @param SchemeGeneratorOptions $options
     * @param DateTime $date
     * @param int $recursionCount
     * @return mixed
     */
    protected function selectMatchesForRound(int $round, SchemeGeneratorOptions $options, DateTime $date, int $recursionCount = 0)
    {
        $players = $options->getPlayers();

        $possiblePlayers = $this->selectPlayersForThisRound($options, $date);

        $countsPerTeam = $this->schemeData->getNumberOfMatchesPerTeam();
        $teamsWithMostMatches = [];
        if (count($countsPerTeam) > 0 && max($countsPerTeam) > 0) {
            $teamsWithMostMatches = array_keys($countsPerTeam, max($countsPerTeam));
        }

        $playersToInclude = [];
        $playersToExclude = [];
        if (max($this->totalCountPerPlayer) - min($this->totalCountPerPlayer) == 2) {
            $playersToInclude = array_keys($this->totalCountPerPlayer, min($this->totalCountPerPlayer));
            $playersToInclude = array_intersect($playersToInclude, $possiblePlayers);
            $playersToInclude = array_slice($playersToInclude, 0, $options->getMaxPlayersPerRound() - 1);

            $playersToExclude = array_keys($this->totalCountPerPlayer, max($this->totalCountPerPlayer));
            $playersToExclude = array_slice($playersToExclude, 0, 1);
        }

        $selectedPermutation = current($this->permutations);
        shuffle($this->permutations);
        foreach ($this->permutations as $permutation) {

            foreach ($playersToInclude as $includePlayer) {
                if ($playersToInclude !== null && !in_array($includePlayer, $permutation)) {
                    continue 2;
                }
            }

            // Pick a permutation where all possible players apply
            foreach ($permutation as $playerIndex) {
                if (!isset($possiblePlayers[$playerIndex])) {
                    continue 2;
                }

                if (count($playersToExclude) && in_array($playerIndex, $playersToExclude)) {
                    continue 2;
                }
            }

            //@todo check team

            $selectedPermutation = $permutation;
            break;
        }

        $count = 0;
        $matches = [];

        foreach ($selectedPermutation as $playerIndex) {
            if ($count % 4 == 0) {
                $match = new Match();
                $match->setRound($round);
                $match->setDateTime($date);
                $matches[] = $match;
            }
            if ($count % 2 == 0) {
                $team = new Team();
                $match->addTeam($team);
            }

            $team->addPlayer($players[$playerIndex]);

            if ($team->getPlayerCount() == 2 && in_array($team->getUniqueTeamId(), $teamsWithMostMatches) && $recursionCount < 200) {
                return $this->selectMatchesForRound($round, $options, $date, ++$recursionCount);
            }

            $count++;
        }

        /** @var Match $match */
        foreach ($matches as $match) {
            foreach ($match->getPlayers() as $player) {
                $playerIndex = $this->lookupPlayerIndex($player);
                $this->iterationCountPerPlayer[$playerIndex]++;
                $this->totalCountPerPlayer[$playerIndex]++;
            }
            $this->schemeData->addMatch($match);
        }
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
        }

        if (count($playersForThisRound) < $options->getMaxPlayersPerRound()) {
            throw new NotEnoughPlayersException(sprintf('Too few players on date %s', $date->format('d-m-Y')));
        }
        return $playersForThisRound;
    }

    /**
     * @param Player $playerToLookup
     * @return int|null
     */
    protected function lookupPlayerIndex(Player $playerToLookup)
    {
        foreach ($this->players as $index => $player) {
            if ($player === $playerToLookup) {
                return $index;
            }
        }
        return null;
    }

    /**
     * Create all teams
     */
    protected function createTeams()
    {
        foreach ($this->players as $playerA) {
            foreach ($this->players as $playerB) {
                if ($playerA === $playerB) {
                    continue;
                }
                $team = new Team();
                $team->addPlayer($playerA);
                $team->addPlayer($playerB);
                $this->teams[$team->getUniqueTeamId()] = $team;
            }
        }
        return $this->teams;
    }

    /**
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }
}
