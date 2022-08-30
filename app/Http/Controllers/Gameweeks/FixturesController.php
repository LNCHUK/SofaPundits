<?php

namespace App\Http\Controllers\Gameweeks;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPredictions\UpdateRequest as UpdatePredictionsRequest;
use App\Models\Gameweek;
use App\Models\Group;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FixturesController extends Controller
{
    /**
     * Show a page allowing the user to set or edit the fixtures for the chosen
     * Gameweek.
     *
     * @param Group $group
     * @param Gameweek $gameweek
     * @return Renderable
     */
    public function edit(Group $group, Gameweek $gameweek): Renderable
    {
        return view('gameweeks.fixtures.edit', compact('group', 'gameweek'));
    }

    /**
     * Store the Gameweek fixtures in the database.
     *
     * @param UpdatePredictionsRequest $request
     * @param Group $group
     * @param Gameweek $gameweek
     * @return RedirectResponse
     */
    public function update(Request $request, Group $group, Gameweek $gameweek)
    {
        // TODO: Need some validation bro...

        $gameweek->fixtures()->sync($request->selected_fixtures);

        return redirect()->route('gameweeks.show', ['group' => $group, 'gameweek' => $gameweek]);
    }
}
