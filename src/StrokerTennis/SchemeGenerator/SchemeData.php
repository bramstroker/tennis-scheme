<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 09:10
 */

namespace StrokerTennis\SchemeGenerator;


use StrokerTennis\Model\Match;
use StrokerTennis\Model\Player;

class SchemeData
{
    /**
     * @var array
     */
    protected $matchesPerRound = [];

    /**
     * @param Match $match
     */
    public function addMatch(Match $match)
    {
        if (!isset($this->matchesPerRound[$match->getRound()])) {
            $this->matchesPerRound[$match->getRound()] = [];
        }
        array_push($this->matchesPerRound[$match->getRound()], $match);
    }

    /**
     * @return int[]
     */
    public function getRounds()
    {
        return array_keys($this->matchesPerRound);
    }

    /**
     * @param $round
     * @return Match[]
     */
    public function getMatchesForRound($round)
    {
        if (array_key_exists($round, $this->matchesPerRound)) {
            return $this->matchesPerRound[$round];
        }
        return [];
    }

    /**
     * @param $round
     * @return Player[]
     */
    public function getPlayersForRound($round)
    {
        $players = [];
        foreach ($this->getMatchesForRound($round) as $match) {
            foreach ($match->getTeams() as $team) {
                foreach ($team->getPlayers() as $player) {
                    $players[] = $player;
                }
            }
        }
        return $players;
    }

    /**
     * Reset all data
     */
    public function reset()
    {
        $this->matchesPerRound = [];
    }
}