<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 17-3-2016
 * Time: 22:15
 */

namespace StrokerTennis\Specification;


use DateTime;
use StrokerTennis\Model\Player;

class AbsenceSpecification implements SpecificationInterface
{
    /** @var DateTime */
    protected $startDate;

    /** @var DateTime */
    protected $endDate;

    /** @var Player */
    protected $player;

    public function __construct(Player $player, DateTime $startDate, DateTime $endDate = null)
    {
        $this->player = $player;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @param Player $player
     * @param DateTime $dateTime
     * @return mixed
     */
    public function isSatisfiedBy(Player $player, DateTime $dateTime)
    {
        if ($this->player !== $player) {
            return false;
        }

        if ($this->endDate == null && $dateTime == $this->startDate) {
            return true;
        }

        if ($dateTime >= $this->startDate && $dateTime <= $this->endDate) {
            return true;
        }
        return false;
    }
}
