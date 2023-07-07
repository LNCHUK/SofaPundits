<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class RankCollectionByScore
{
    public static function rankCollection(Collection $collectionToRank, string $keyToRankAgainst)
    {
        $previousResult = null;
        $position = 0;
        $tiedScores = 0;

        return $collectionToRank->map(function (object $playerRecord) use (&$previousResult, &$position, &$tiedScores, $keyToRankAgainst) {
            if ($playerRecord->{$keyToRankAgainst} !== $previousResult) {
                // If the score is different, we can assign increase the position and assign it
                $position = $position + $tiedScores + 1;
                $playerRecord->position = $position;
                $tiedScores = 0;
                $previousResult = $playerRecord->{$keyToRankAgainst};
            } else if ($playerRecord->{$keyToRankAgainst} === $previousResult) {
                // In the case of a tie, we don't increment the position, we increment the ties and assign the position
                $tiedScores++;
                $playerRecord->position = $position;
            }
        });
    }
}