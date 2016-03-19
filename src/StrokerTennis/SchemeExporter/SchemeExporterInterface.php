<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 09:22
 */

namespace StrokerTennis\SchemeExporter;


use StrokerTennis\SchemeGenerator\SchemeData;

interface SchemeExporterInterface
{
    /**
     * @param SchemeData $schemeData
     * @param array $params
     * @return mixed
     */
    public function export(SchemeData $schemeData, $params = []);
}
