<?php

namespace App\Http\Controllers\Gameweeks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gameweeks\StoreRequest;
use App\Http\Requests\Gameweeks\UpdateRequest;
use App\Models\Gameweek;
use App\Models\Group;
use App\Notifications\GameweekPublished;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return redirect()->route('gameweeks.show', [
            'group' => $group,
            'gameweek' => $group->gameweeks()->create($request->validated())
        ]);
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

        return redirect()->route('gameweeks.show', compact('group', 'gameweek'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Group $group
     * @param Gameweek $gameweek
     * @return RedirectResponse
     */
    public function publish(Request $request, Group $group, Gameweek $gameweek): RedirectResponse
    {
        $gameweek->update(['published_at' => now()]);

        
//        auth()->user()->notify(new GameweekPublished($gameweek));

        // TODO: Trigger an event here so we can hook notifications into it

        return redirect()->route('gameweeks.show', compact('group', 'gameweek'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Group $group
     * @param Gameweek $gameweek
     * @return RedirectResponse
     */
    public function destroy(Request $request, Group $group, Gameweek $gameweek): RedirectResponse
    {
        $gameweek->delete();

        return redirect()->route('groups.show', $group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Group $group
     * @param Gameweek $gameweek
     * @return RedirectResponse
     */
    public function viewLeaderboard(Group $group, Gameweek $gameweek): Renderable
    {
        $fixtures = $gameweek->fixtures;

        return view('gameweeks.leaderboard', compact('group', 'gameweek', 'fixtures'));
    }
}
