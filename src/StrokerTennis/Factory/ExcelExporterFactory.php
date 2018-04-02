<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 13:44
 */

namespace StrokerTennis\Factory;


use Interop\Container\ContainerInterface;
use StrokerTennis\SchemeExporter\ExcelExporter;

class ExcelExporterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ExcelExporter(
            new \PHPExcel()
        );
    }
}