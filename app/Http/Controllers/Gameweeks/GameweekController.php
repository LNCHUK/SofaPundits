<?php

namespace App\Http\Controllers\Gameweeks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gameweeks\StoreRequest;
use App\Http\Requests\Gameweeks\UpdateRequest;
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
        $gameweek = $group->gameweeks()->create($request->validated());

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
        $gameweek->update($request->validated());

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
}
