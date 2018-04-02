<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 09:12
 */

namespace StrokerTennis\Model;


class Team
{
    /** @var array */
    protected $players = [];

    public function __construct($players = [])
    {
        $this->players = $players;
    }

    public function addPlayer(Player $player)
    {
        $this->players[] = $player;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getPlayerCount()
    {
        return count($this->players);
    }

    /**
     * @return string
     */
    public function getUniqueTeamId()
    {
        usort($this->players, function(Player $playerA, Player $playerB) {
            return $playerA->getName() > $playerB->getName() ? 1 : -1;
        });
        return implode('|', array_map(function(Player $player) { return $player->getName(); }, $this->players));
    }

    /**
     * @param Team $team
     * @return bool
     */
    public function equals(Team $team)
    {
        return $this->getUniqueTeamId() == $team->getUniqueTeamId();
    }
}
