<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Queries\GetBackedTeamResultsData;
use App\Queries\GetLeaderboardDataByGameweeks;
use App\Queries\GetMostCorrectScoresInASingleWeek;
use App\Queries\GetTotalCorrectScoresForAGroup;
use Illuminate\Http\Request;

class GroupLeaderboardsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Group $group)
    {
        [$mostWeeksWon, $mostWeeksTied, $highestWeeklyScores] = (new GetLeaderboardDataByGameweeks($group))->handle();

        return view('groups.leaderboards', [
            'group' => $group,
            'totalCorrectResultsTable' => (new GetTotalCorrectScoresForAGroup($group))->handle(),
            'mostCorrectScoresInAWeek' => (new GetMostCorrectScoresInASingleWeek($group))->handle(),
            'backedTeamWins' => (new GetBackedTeamResultsData($group))->handle(),
            'mostWeeksWon' => $mostWeeksWon,
            'mostWeeksTied' => $mostWeeksTied,
            'highestWeeklyScores' => $highestWeeklyScores,
        ]);
    }
}
