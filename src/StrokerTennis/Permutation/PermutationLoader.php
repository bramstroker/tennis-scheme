<?php
/**
 * Created by PhpStorm.
 * User: bram
 * Date: 12/16/15
 * Time: 10:12 PM
 */

namespace StrokerTennis\Permutation;


class PermutationLoader
{
    /**
     * @var string
     */
    protected $dataPath;

    /**
     * @param string $dataPath
     */
    public function __construct($dataPath)
    {
        $this->dataPath = $dataPath;
    }

    /**
     * @param int $number Number of items
     * @return array
     * @throws \NotSupportException
     */
    public function getPermutations($number)
    {
        if ($number > 12) {
            throw new \NotSupportException('More than 12 players are not allowed');
        }

        $permutations = [];

        if ($number > 8) { //Load permutations from cache (sample of 10000 permutations)
            $sampleFile = $this->dataPath . 'sample_' . $number . '.txt';
            foreach (file($sampleFile) as $line) {
                $permutations[] = array_map('intval', array_values(explode('-', $line)));
            }
        } else { //Permute realtime
            $permutations = $this->permuteUnique(range(0, $number - 1));
        }

        shuffle($permutations);
        return $permutations;
    }

    /**
     * @param array $items
     * @return array
     */
    protected static function &permuteUnique($items) {
        sort($items);
        $size = count($items);
        $return = [];
        while (true) {
            $return[] = $items;
            $invAt = $size - 2;
            for (;;$invAt--) {
                if ($invAt < 0) {
                    break 2;
                }
                if ($items[$invAt] < $items[$invAt + 1]) {
                    break;
                }
            }
            $swap1Num = $items[$invAt];
            $inv2At = $size - 1;
            while ($swap1Num >= $items[$inv2At]) {
                $inv2At--;
            }
            $items[$invAt] = $items[$inv2At];
            $items[$inv2At] = $swap1Num;
            $reverse1 = $invAt + 1;
            $reverse2 = $size - 1;
            while ($reverse1 < $reverse2) {
                $temp = $items[$reverse1];
                $items[$reverse1] = $items[$reverse2];
                $items[$reverse2] = $temp;
                $reverse1++;
                $reverse2--;
            }
        }
        return $return;
    }
}
