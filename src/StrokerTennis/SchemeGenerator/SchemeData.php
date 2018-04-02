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
     * @var Team[]
     */
    protected $teams = [];

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
     * @param $teams
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
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
     * @return Match[]
     */
    protected function getAllMatches()
    {
        foreach ($this->matchesPerRound as $round => $matches) {
            /** @var Match $match */
            foreach ($matches as $match) {
                yield $match;
            }
        }
    }

    /**
     * @return array
     */
    public function getNumberOfMatchesPerTeam()
    {
        $countPerTeam = [];
        foreach ($this->teams as $teamId => $team) {
            $countPerTeam[$teamId] = 0;
            foreach ($this->getAllMatches() as $match) {
                if ($match->hasTeam($team)) {
                    $countPerTeam[$teamId]++;
                }
            }
        }

        asort($countPerTeam);
        return $countPerTeam;
    }

    /**
     * Reset all data
     */
    public function reset()
    {
        $this->matchesPerRound = [];
    }
}
