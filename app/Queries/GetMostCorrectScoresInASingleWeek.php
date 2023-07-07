<?php

namespace App\Queries;

use App\Helpers\RankCollectionByScore;
use App\Models\Group;
use App\Models\UserPrediction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GetMostCorrectScoresInASingleWeek
{
    public function __construct(
        private readonly Group $group
    )
    {}

    public function handle()
    {
        $subQuery = UserPrediction::query()
            ->selectRaw('user_id, gameweek_id, COUNT(*) AS score')
            ->join('fixtures', 'fixtures.id', '=', 'user_predictions.fixture_id')
            ->join('gameweeks', 'gameweeks.id', '=', 'user_predictions.gameweek_id')
            ->where(function ($query) {
                $query->whereRaw("user_predictions.home_score = JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.home')")
                    ->whereRaw("user_predictions.away_score = JSON_EXTRACT(`fixtures`.`score` , '$.fulltime.away')");
            })
            ->where('group_id', $this->group->id)
            ->groupBy('gameweek_id', 'user_id');

        $results = DB::query()
            ->selectRaw("users.first_name, users.last_name, user_id, gameweek_id, score, gameweeks.uuid")
            ->fromSub($subQuery, 'most_correct_scores')
            ->join('users', 'users.id', '=', 'most_correct_scores.user_id')
            ->join('gameweeks', 'gameweeks.id', '=', 'most_correct_scores.gameweek_id')
            ->orderBy('score', 'DESC')
            ->get();

        $results = $results->unique('user_id');

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