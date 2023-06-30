<?php

namespace App\Service\Data;

use App\Entity\Metrics;

class Ranking
{
    public function getRanking(array $data, int $sort): array
    {
        if ($sort == SORT_ASC) {
            asort($data);
        }
        if ($sort == SORT_DESC) {
            arsort($data);
        }

        $rankedData = [];
        $rank = 0;
        $step = 0;
        $lastValue = null;
        foreach ($data as $key => $value) {
            if ($value != $lastValue) {
                $lastValue = $value;
                $rank = $rank+1+$step;
                $rankedData[$key] = $rank;
                $step=0;
            }
            else {
                $step++;
                $rankedData[$key] = $rank;
            }
        }
        ksort($rankedData);
        return $rankedData;
    }



}