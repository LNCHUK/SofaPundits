<?php

namespace App\Queries;

use App\Models\Group;
use App\Models\UserPrediction;
use Illuminate\Support\Facades\DB;

class GetTotalCorrectScoresForAGroup
{
    public function __construct(
        private readonly Group $group
    )
    {}

    public function handle()
    {
        $correctResultsQuery = UserPrediction::query()
            ->selectRaw('user_id, COUNT(*) as score')
            ->join('fixtures', 'fixtures.id', '=', 'user_predictions.fixture_id')
            ->join('gameweeks', 'gameweeks.id', '=', 'user_predictions.gameweek_id')
            ->where(function ($query) {
                $query->whereRaw("user_predictions.home_score = JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.home')")
                    ->whereRaw("user_predictions.away_score = JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.away')");
            })
            ->where('group_id', $this->group->id)
            ->groupBy('user_id');

        $results = DB::query()
            ->selectRaw("users.first_name, users.last_name, user_id, score")
            ->fromSub($correctResultsQuery, 'correct_scores_table')
            ->join('users', 'users.id', '=', 'correct_scores_table.user_id')
            ->orderBy('score', 'DESC')
            ->get();

        $previousResult = null;
        $position = 0;
        $tiedScores = 0;

        $results->map(function (object $playerRecord) use (&$previousResult, &$position, &$tiedScores) {
            if ($playerRecord->score !== $previousResult) {
                // If the score is different, we can assign increase the position and assign it
                $position = $position + $tiedScores + 1;
                $playerRecord->position = $position;
                $tiedScores = 0;
                $previousResult = $playerRecord->score;
            } else if ($playerRecord->score === $previousResult) {
                // In the case of a tie, we don't increment the position, we increment the ties and assign the position
                $tiedScores++;
                $playerRecord->position = $position;
            }
        });

        return $results;
    }
}