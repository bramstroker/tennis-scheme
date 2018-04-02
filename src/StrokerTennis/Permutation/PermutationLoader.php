<?php
/**
 * Created by PhpStorm.
 * User: bram
 * Date: 12/16/15
 * Time: 10:12 PM
 */

namespace StrokerTennis\Permutation;


use drupol\phpermutations\Generators\Permutations;

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
     * @param int $numPlayers
     * @param int|null $length
     * @return array
     * @throws \Exception
     */
    public function getPermutations(int $numPlayers, int $length = null)
    {
        if ($length === null) {
            $length = $numPlayers;
        }

        if ($numPlayers > 12) {
            throw new \InvalidArgumentException('More than 12 players are not allowed');
        }

        $permutations = [];

        if ($numPlayers > 8) { //Load permutations from cache (sample of 10000 permutations)
            $file = $this->dataPath . 'permutations_' . $numPlayers . '_' . $length . '.txt';
            if (!file_exists($file)) {
                throw new \Exception('Permutation file ' . $file . ' not found. Generate using bin/generate_permuation_files.php');
            }

            $handle = fopen($file, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $permutations[] = array_map('intval', array_values(explode('-', $line)));
                }

                fclose($handle);
            }
        } else {
            $permutations = $this->permuteRealTime(range(0, $numPlayers - 1), $length);
        }

        shuffle($permutations);
        return $permutations;
    }

    /**
     * @param array $dataSet
     * @param int $length
     * @return array
     */
    protected function permuteRealTime(array $dataSet, int $length)
    {
        $permutations = new Permutations($dataSet, $length);
        return $permutations->toArray();
    }
}
