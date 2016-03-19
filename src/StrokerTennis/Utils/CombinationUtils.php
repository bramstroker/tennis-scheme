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
        $count = sizeof($array);
        for ($i = $start_i; $i < $count; $i++) {
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
}
