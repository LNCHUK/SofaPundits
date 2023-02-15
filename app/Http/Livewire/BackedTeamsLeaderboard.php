<?php

namespace App\Http\Livewire;

use App\Data\BackedTeamLeaderboardPosition;
use App\Models\ApiFootball\Team;
use App\Models\BackedTeamResults;
use App\Models\User;
use Livewire\Component;

class BackedTeamsLeaderboard extends Component
{
    public $group;

    public function render()
    {
        $backedTeamResults = BackedTeamResults::query()
            ->where('group_id', $this->group->id)
            ->get();

        $position = 0;
        $previousScore = null;
        $skipped = 0;

        $backedTeamLeaderboard = $this->group->users()
            ->with('backedTeams')
            ->get()
            ->map(function (User $user) use ($backedTeamResults) {
                return new BackedTeamLeaderboardPosition(
                    user: $user,
                    team: optional($user->backedTeams->firstWhere('group_id', $this->group->id))->team
                    ?? new Team([
                        'name' => '-'
                    ]),
                    correct_scores: optional($backedTeamResults->firstWhere('user_id', $user->id))->correct_scores
                    ?? 0,
                    position: 1
                );
            })
            ->sortByDesc(fn (BackedTeamLeaderboardPosition $data) => $data->getCorrectScores())
            ->map(function (BackedTeamLeaderboardPosition $data) use (&$position, &$previousScore, &$skipped) {
                if ($previousScore === (int) $data->getCorrectScores()) {
                    // We have a tie, leave the position but increment the skip
                    $skipped++;
                } else {
                    $position += $skipped + 1;
                    $skipped = 0;
                }

                // Assign the points to use in the next iteration, to confirm a tie
                $previousScore = (int) $data->getCorrectScores();
                $data->setPosition($position);

                return $data;
            });

        return view('livewire.backed-teams-leaderboard', [
            'leaderboardData' => $backedTeamLeaderboard
        ]);
    }
}
