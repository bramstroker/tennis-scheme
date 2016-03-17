<?php
/**
 * Created by PhpStorm.
 * User: bram
 * Date: 12/16/15
 * Time: 6:06 PM
 */

namespace StrokerTennis;

use DatePeriod;
use PHPExcel;
use PHPExcel_IOFactory;
use StrokerTennis\Permutation\PermutationLoader;

class SchemeGenerator
{
    /** @var PHPExcel */
    protected $phpExcel;

    /** @var PermutationLoader */
    protected $permutationLoader;

    

    /** @var DatePeriod */
    protected $datePeriod;

    public function __construct(PHPExcel $phpExcel, PermutationLoader $permutationLoader)
    {
        $this->phpExcel = $phpExcel;
        $this->permutationLoader = $permutationLoader;
    }

    /**
     * @param string $filename
     * @param SchemeGeneratorOptions $options
     * @throws \NotSupportException
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function generate($filename = 'tennis.xls', SchemeGeneratorOptions $options)
    {
        $countPerPlayer = array_fill(0, count($options->getPlayers()), 0);

        $players = $options->getPlayers();
        
        $permutations = $this->permutationLoader->getPermutations(count($options->getPlayers()));

        $workSheet = $this->phpExcel->getActiveSheet();
        $row = 1;
        foreach($options->getDatePeriod() as $i => $date) {
            $columns = [];
            foreach ($permutations[$i] as $countPlayers => $playerIndex) {
                if ($countPlayers == $options->getMaxPlayersPerRound()) {
                    break;
                }
                $countPerPlayer[(int) $playerIndex]++;
                if ($countPerPlayer[(int) $playerIndex] == max($countPerPlayer)) {
                    continue;
                }
                $columns[] = $players[(int) $playerIndex]->getName();
            }

            $workSheet->fromArray($columns, null, 'B' . $row);
            $workSheet->setCellValue('A' . $row, $date->format('d-m-Y'));
            $row++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, "Excel5");
        $objWriter->save($filename);

        var_dump($countPerPlayer);
    }

    /**
     * @param array $players
     */
    public function setPlayers($players = [])
    {
        $this->players = $players;
    }

    /**
     * @param \DatePeriod $datePeriod
     */
    public function setDatePeriod($datePeriod)
    {
        $this->datePeriod = $datePeriod;
    }
} 