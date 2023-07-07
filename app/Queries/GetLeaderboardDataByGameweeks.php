<?php

namespace App\Queries;

use App\Helpers\RankCollectionByScore;
use App\Models\Gameweek;
use App\Models\Group;
use App\Models\User;
use App\Models\UserPrediction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GetLeaderboardDataByGameweeks
{
    public function __construct(
        private readonly Group $group
    )
    {}

    public function handle()
    {
        $users = $this->group->users
            ->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'weeks_won' => 0,
                    'weeks_tied' => 0,
                    'highest_weekly_score' => 0,
                ];
            })
            ->keyBy('id')
            ->toArray();

        // Loop over all gameweeks to track data
        Gameweek::query()
            ->where('group_id', $this->group->id)
            ->orderBy('start_date')
            ->get()
            ->each(function (Gameweek $gameweek) use (&$users) {
                $players = $gameweek->getPlayersOrderedByPoints();
                $unkeyedPlayers = array_values($players->toArray());

                if ($unkeyedPlayers[0]['points'] != $unkeyedPlayers[1]['points']) {
                    // If the top two scores are different, we have a clear winner
                    $users[$unkeyedPlayers[0]['id']]['weeks_won']++;
                } else {
                    // If the top two scores are the same, we have at least a 2-way tie
                    $lastTiedPlayerIndex = 1;
                    while ($unkeyedPlayers[0]['points'] == $unkeyedPlayers[$lastTiedPlayerIndex + 1]['points']) {
                        $lastTiedPlayerIndex++;
                    }

                    // For all the tied players, increment that counter
                    for ($i = 0; $i <= $lastTiedPlayerIndex; $i++) {
                        $users[$unkeyedPlayers[$i]['id']]['weeks_tied']++;
                    }
                }

                // Lastly, loop over weekly scores and update highest score if it's higher
                foreach ($unkeyedPlayers as $player) {
                    if ($player['points'] > $users[$player['id']]['highest_weekly_score']) {
                        $users[$player['id']]['highest_weekly_score'] = $player['points'];
                    }
                }
            });

        $mostWeeksWon = $this->orderPlayersByWeeksWon(collect($users));
        $mostWeeksTied = $this->orderPlayersByWeeksTied(collect($users));
        $highestWeeklyScores = $this->orderPlayersByHighestScore(collect($users));

        return [$mostWeeksWon, $mostWeeksTied, $highestWeeklyScores];
    }

    private function orderPlayersByWeeksWon($userData)
    {
        return $this->orderCollectionByKey($userData->sortByDesc('weeks_won'), 'weeks_won');
    }

    private function orderPlayersByWeeksTied($userData)
    {
        return $this->orderCollectionByKey($userData->sortByDesc('weeks_tied'), 'weeks_tied');
    }

    private function orderPlayersByHighestScore($userData)
    {
        return $this->orderCollectionByKey($userData->sortByDesc('highest_weekly_score'), 'highest_weekly_score');
    }

    private function orderCollectionByKey($collection, $key)
    {
        $previousResult = null;
        $position = 0;
        $tiedScores = 0;

        return $collection->map(function ($playerRecord) use (&$previousResult, &$position, &$tiedScores, $key) {
            if ($playerRecord[$key] !== $previousResult) {
                // If the score is different, we can assign increase the position and assign it
                $position = $position + $tiedScores + 1;
                $playerRecord['position'] = $position;
                $tiedScores = 0;
                $previousResult = $playerRecord[$key];
            } else if ($playerRecord[$key] === $previousResult) {
                // In the case of a tie, we don't increment the position, we increment the ties and assign the position
                $tiedScores++;
                $playerRecord['position'] = $position;
            }

            return $playerRecord;
        });
    }
}