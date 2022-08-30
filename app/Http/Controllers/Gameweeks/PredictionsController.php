<?php

namespace App\Http\Controllers\Gameweeks;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPredictions\UpdateRequest as UpdatePredictionsRequest;
use App\Models\Gameweek;
use App\Models\Group;
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
     * Store the User's predictions in the database.
     *
     * @param UpdatePredictionsRequest $request
     * @param Group $group
     * @param Gameweek $gameweek
     * @return RedirectResponse
     */
    public function update(UpdatePredictionsRequest $request, Group $group, Gameweek $gameweek)
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
}
