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
    protected $specifications;
    
    public function __construct(DatePeriod $datePeriod, $players = [])
    {
        $this->datePeriod = $datePeriod;
        $this->players = $players;
    }

    /**
     * @return Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }
    
    /**
     * @return DatePeriod
     */
    public function getDatePeriod()
    {
        return $this->datePeriod;
    }

    /**
     * @return int
     */
    public function getMaxPlayersPerRound()
    {
        if ($this->maxPlayersPerRound === null) {
            return count($this->players);
        }
        return $this->maxPlayersPerRound;
    }

    /**
     * @param int $maxPlayersPerRound
     */
    public function setMaxPlayersPerRound($maxPlayersPerRound)
    {
        $this->maxPlayersPerRound = $maxPlayersPerRound;
    }

    /**
     * @param DateTime[] $excludeDates
     */
    public function setExcludeDates($excludeDates)
    {
        $this->excludeDates = $excludeDates;
    }

    /**
     * @return \DateTime[]
     */
    public function getExcludeDates()
    {
        return $this->excludeDates;
    }
}