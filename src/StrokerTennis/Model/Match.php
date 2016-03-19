<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 09:12
 */

namespace StrokerTennis\Model;


use DateTime;

class Match
{
    /** @var Team[] */
    protected $teams = [];

    /** @var DateTime */
    protected $dateTime;

    /** @var int */
    protected $round;

    /**
     * @param Team $team
     */
    public function addTeam(Team $team)
    {
        $this->teams[] = $team;
    }

    /**
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param DateTime $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return int
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @param int $round
     */
    public function setRound($round)
    {
        $this->round = (int) $round;
    }
}
