<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 17-3-2016
 * Time: 20:17
 */

namespace StrokerTennis\SchemeGenerator;


use DatePeriod;
use DateTime;
use StrokerTennis\Model\Player;
use StrokerTennis\Specification\SpecificationInterface;

class SchemeGeneratorOptions
{
    /** @var Player[] */
    protected $players = [];

    /** @var DatePeriod */
    protected $datePeriod;

    /** @var DateTime[] */
    protected $excludeDates = [];

    /** @var int */
    protected $maxPlayersPerRound;
    
    /** @var SpecificationInterface[] */
    protected $specifications = [];

    /**
     * SchemeGeneratorOptions constructor.
     * @param DatePeriod $datePeriod
     */
    public function __construct(DatePeriod $datePeriod)
    {
        $this->datePeriod = $datePeriod;
    }

    /**
     * @return Player[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }
    
    /**
     * @return DatePeriod
     */
    public function getDatePeriod(): DatePeriod
    {
        return $this->datePeriod;
    }

    /**
     * @return int
     */
    public function getMaxPlayersPerRound(): int
    {
        if ($this->maxPlayersPerRound === null) {
            return \count($this->players);
        }
        return $this->maxPlayersPerRound;
    }

    /**
     * @param int $maxPlayersPerRound
     * @return SchemeGeneratorOptions
     */
    public function setMaxPlayersPerRound($maxPlayersPerRound): SchemeGeneratorOptions
    {
        $this->maxPlayersPerRound = $maxPlayersPerRound;
        return $this;
    }

    /**
     * @param DateTime[] $excludeDates
     * @return SchemeGeneratorOptions
     */
    public function setExcludeDates($excludeDates): SchemeGeneratorOptions
    {
        $this->excludeDates = $excludeDates;
        return $this;
    }

    /**
     * @return \DateTime[]
     */
    public function getExcludeDates(): array
    {
        return $this->excludeDates;
    }

    /**
     * @param SpecificationInterface $specification
     * @return SchemeGeneratorOptions
     */
    public function addSpecification(SpecificationInterface $specification): SchemeGeneratorOptions
    {
        $this->specifications[] = $specification;
        return $this;
    }

    /**
     * @return SpecificationInterface[]
     */
    public function getSpecifications(): array
    {
        return $this->specifications;
    }

    /**
     * @param Player[] $players
     * @return SchemeGeneratorOptions
     */
    public function setPlayers(array $players): SchemeGeneratorOptions
    {
        $this->players = $players;
        return $this;
    }

    /**
     * @param Player $player
     * @return SchemeGeneratorOptions
     */
    public function addPlayer(Player $player): SchemeGeneratorOptions
    {
        $this->players[] = $player;
        return $this;
    }
}
