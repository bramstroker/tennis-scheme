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

class CompositeOrSpecification implements SpecificationInterface
{
    /**
     * @var SpecificationInterface[]
     */
    private $specifications;

    /**
     * @param SpecificationInterface ...$specifications
     */
    public function __construct(SpecificationInterface ...$specifications)
    {
        $this->specifications = $specifications;
    }

    /**
     * if at least one specification is true, return true, else return false
     *
     * @param Player $player
     * @param DateTime $dateTime
     * @return bool
     */
    public function isSatisfiedBy(Player $player, DateTime $dateTime): bool
    {
        foreach ($this->specifications as $specification) {
            if ($specification->isSatisfiedBy($player, $dateTime)) {
                return true;
            }
        }
        return false;
    }
}
