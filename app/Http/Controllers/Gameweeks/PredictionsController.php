<?php

namespace App\Http\Controllers\Gameweeks;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPredictions\UpdateRequest as UpdatePredictionsRequest;
use App\Models\Gameweek;
use App\Models\Group;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class PredictionsController extends Controller
{
    /**
     * Show a page allowing the user to set or edit their predictions for the chosen
     * Gameweek's fixtures.
     *
     * @param Group $group
     * @param Gameweek $gameweek
     * @return Renderable
     */
    public function edit(Group $group, Gameweek $gameweek): Renderable
    {
        $this->authorize('updatePredictions', $gameweek);

        $gameweek->load('activeUserPredictions');

        return view('gameweeks.predictions', compact('group', 'gameweek'));
    }

    /**
     * @param Group $group
     * @param User $user
     * @param Gameweek $gameweek
     * @return Renderable
     */
    public function editGroupUserPredictions(Group $group, User $user, Gameweek $gameweek): Renderable
    {
        return view('gameweeks.predictions', compact('group', 'gameweek', 'user'));
    }

    /**
     * Store the User's predictions in the database.
     *
     * @param UpdatePredictionsRequest $request
     * @param Group $group
     * @param Gameweek $gameweek
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdatePredictionsRequest $request, Group $group, Gameweek $gameweek): RedirectResponse
    {
        $this->authorize('updatePredictions', $gameweek);

        foreach ($request->fixtures as $fixtureId => $scores) {
            if (is_null($scores['home_score']) || is_null($scores['away_score'])) {
                continue;
            }

            $gameweek->predictions()->updateOrCreate([
                'fixture_id' => $fixtureId,
                'user_id' => auth()->id(),
            ], $scores);
        }

        return redirect()->route('gameweeks.show', compact('gameweek', 'group'));
    }

    /**
     * Store the User's predictions in the database.
     *
     * @param UpdatePredictionsRequest $request
     * @param Group $group
     * @param Gameweek $gameweek
     * @param User $user
     * @return RedirectResponse
     */
    public function updateUserPredictions(
        UpdatePredictionsRequest $request,
        Group $group,
        User $user,
        Gameweek $gameweek
    ) {
        $this->authorize('updatePredictionsForUser', [$gameweek, $user]);

        foreach ($request->fixtures as $fixtureId => $scores) {
            if (is_null($scores['home_score']) || is_null($scores['away_score'])) {
                continue;
            }

            $gameweek->predictions()->updateOrCreate([
                'fixture_id' => $fixtureId,
                'user_id' => $user->id,
            ], $scores);
        }

        return redirect()->route('gameweeks.view-leaderboard', compact('gameweek', 'group'));
    }
}
