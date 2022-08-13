<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gameweeks\StoreRequest;
use App\Http\Requests\Gameweeks\UpdateRequest;
use App\Http\Requests\UserPredictions\UpdateRequest as UpdatePredictionsRequest;
use App\Models\Gameweek;
use App\Models\Group;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class GameweekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Group $group
     * @return Renderable
     */
    public function index(Group $group): Renderable
    {
        return view('gameweeks.index', compact('group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Group $group
     * @return Renderable
     */
    public function create(Group $group): Renderable
    {
        return view('gameweeks.create', compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @param Group $group
     * @return RedirectResponse
     */
    public function store(StoreRequest $request, Group $group): RedirectResponse
    {
        // Store
        $gameweek = $group->gameweeks()->create($request->all());

        // Redirect
        return redirect()->route('gameweeks.show', ['group' => $group, 'gameweek' => $gameweek]);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @param Gameweek $gameweek
     * @return Renderable
     */
    public function show(Group $group, Gameweek $gameweek): Renderable
    {
        $gameweek->load('activeUserPredictions');

        return view('gameweeks.show', compact('group', 'gameweek'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Group $group
     * @param Gameweek $gameweek
     * @return Response
     */
    public function edit(Group $group, Gameweek $gameweek): Renderable
    {
        return view('gameweeks.edit', compact('group', 'gameweek'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Group $group
     * @param Gameweek $gameweek
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, Group $group, Gameweek $gameweek): RedirectResponse
    {
        $gameweek->update($request->all());

        $gameweek->fixtures()->sync($request->selected_fixtures);

        return redirect()->route('gameweeks.show', ['group' => $group, 'gameweek' => $gameweek]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Gameweek $gameweek
     * @return Response
     */
    public function destroy(Gameweek $gameweek)
    {
        //
    }

    /**
     * Show a page allowing the user to set or edit their predictions for the chosen
     * Gameweek's fixtures.
     *
     * @param Group $group
     * @param Gameweek $gameweek
     * @return Renderable
     */
    public function editPredictions(Group $group, Gameweek $gameweek): Renderable
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
    public function updatePredictions(UpdatePredictionsRequest $request, Group $group, Gameweek $gameweek)
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
