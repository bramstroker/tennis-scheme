<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 17-3-2016
 * Time: 20:06
 */

namespace StrokerTennis\Model;


class Player
{
    /** @var string */
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
