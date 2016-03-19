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
}