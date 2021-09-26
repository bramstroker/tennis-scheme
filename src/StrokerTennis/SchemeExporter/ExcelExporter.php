<?php
/**
 * Created by PhpStorm.
 * User: Bram
 * Date: 19-3-2016
 * Time: 09:23
 */

namespace StrokerTennis\SchemeExporter;


use IntlDateFormatter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use StrokerTennis\Model\Match;
use StrokerTennis\SchemeGenerator\SchemeData;

class ExcelExporter implements SchemeExporterInterface
{
    /**
     * @param SchemeData $schemeData
     * @param array $options
     * @return void
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function export(SchemeData $schemeData, $options = [])
    {
        $spreadsheet = new Spreadsheet();

        $dateFormatter = new IntlDateFormatter('nl', null, null);
        $dateFormatter->setPattern($options['dateformat'] ?? 'd - MMM');
        $workSheet = $spreadsheet->getActiveSheet();
        $row = 1;
        foreach ($schemeData->getRounds() as $round) {
            $columns = [];
            /** @var Match $firstMatch */
            $firstMatch = current($schemeData->getMatchesForRound($round));
            $workSheet->setCellValue('A' . $row, $dateFormatter->format($firstMatch->getDateTime()));

            foreach ($schemeData->getPlayersForRound($round) as $player) {
                $columns[] = $player->getName();
            }
            $workSheet->fromArray($columns, null, 'B' . $row);

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = $options['filename'] ?? 'scheme.xls';
        $writer->save($filename);
    }
}
