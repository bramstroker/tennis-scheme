<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 17-3-2016
 * Time: 20:17
 */

namespace StrokerTennis;


use DatePeriod;
use StrokerTennis\ValueObject\Player;

class SchemeGeneratorOptions
{
    /** @var Player[] */
    protected $players = [];

    /** @var DatePeriod */
    protected $datePeriod;

    /** @var int */
    protected $maxPlayersPerRound;

    public function __construct(DatePeriod $datePeriod, $players = [])
    {
        $this->datePeriod = $datePeriod;
        $this->players = $players;
    }

    /**
     * @return ValueObject\Player[]
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
}