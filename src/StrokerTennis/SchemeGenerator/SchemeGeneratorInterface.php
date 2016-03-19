<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 11:18
 */

namespace StrokerTennis\SchemeGenerator;

interface SchemeGeneratorInterface
{
    /**
     * @param SchemeGeneratorOptions $options
     * @return SchemeData
     */
    public function generate(SchemeGeneratorOptions $options);
}