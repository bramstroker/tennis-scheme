<?php
/**
 * Created by PhpStorm.
 * User: bram
 * Date: 12/16/15
 * Time: 6:14 PM
 */

namespace StrokerTennis\Utils;


class CombinationUtils
{
    public static function computePermutations(&$array, &$results, $start_i = 0)
    {
        if ($start_i == sizeof($array)-1) {
            array_push($results, $array);
        }
        for ($i = $start_i; $i<sizeof($array); $i++) {
            //Swap array value at $i and $start_i
            $t = $array[$i]; $array[$i] = $array[$start_i]; $array[$start_i] = $t;

            //Recurse
            self::computePermutations($array, $results, $start_i+1);

            //Restore old order
            $t = $array[$i]; $array[$i] = $array[$start_i]; $array[$start_i] = $t;
        }
    }

    public static function permuteUnique($items, $perms = [], &$return = []) {
        if (empty($items)) {
            $return[] = $perms;
        } else {
            sort($items);
            $prev = false;
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $tmp = array_splice($newitems, $i, 1)[0];
                if ($tmp != $prev) {
                    $prev = $tmp;
                    $newperms = $perms;
                    array_unshift($newperms, $tmp);
                    self::permuteUnique($newitems, $newperms, $return);
                }
            }
            return $return;
        }
    }

    public static function &permuteUnique2($items) {
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