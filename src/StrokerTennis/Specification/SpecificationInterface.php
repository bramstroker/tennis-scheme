<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 17-3-2016
 * Time: 20:02
 */

namespace StrokerTennis\Specification;


use DateTime;
use StrokerTennis\Model\Player;

interface SpecificationInterface
{
    /**
     * @param Player $player
     * @param DateTime $dateTime
     * @return mixed
     */
    public function isSatisfiedBy(Player $player, DateTime $dateTime);
}