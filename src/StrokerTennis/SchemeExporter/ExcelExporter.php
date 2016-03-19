<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 09:23
 */

namespace StrokerTennis\SchemeExporter;


use IntlDateFormatter;
use PHPExcel;
use PHPExcel_IOFactory;
use StrokerTennis\Model\Match;
use StrokerTennis\SchemeGenerator\SchemeData;

class ExcelExporter implements SchemeExporterInterface
{
    protected $phpExcel;

    public function __construct(PHPExcel $phpExcel)
    {
        $this->phpExcel = $phpExcel;
    }

    public function export(SchemeData $schemeData, $params = [])
    {
        $dateFormatter = new IntlDateFormatter('nl', null, null);
        $dateFormatter->setPattern('d - MMM');
        $workSheet = $this->phpExcel->getActiveSheet();
        $row = 1;
        foreach ($schemeData->getRounds() as $round) {
            $columns = [];
            /** @var Match $firstMatch */
            $firstMatch = current($schemeData->getMatchesForRound($round));

            foreach ($schemeData->getPlayersForRound($round) as $player) {
                $columns[] = $player->getName();
            }
            $workSheet->fromArray($columns, null, 'B' . $row);
            $workSheet->setCellValue('A' . $row, $dateFormatter->format($firstMatch->getDateTime()));
            $row++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, 'Excel5');
        $objWriter->save($params['filename']);
    }
}