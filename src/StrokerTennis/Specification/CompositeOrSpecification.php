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
    /** @var SpecificationInterface */
    protected $left;

    /** @var SpecificationInterface */
    protected $right;

    public function __construct(SpecificationInterface $left, SpecificationInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @param Player $player
     * @param DateTime $dateTime
     * @return mixed
     */
    public function isSatisfiedBy(Player $player, DateTime $dateTime)
    {
        return ($this->left->isSatisfiedBy($player, $dateTime) || $this->right->isSatisfiedBy($player, $dateTime));
    }
}
