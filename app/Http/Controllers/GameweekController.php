<?php

namespace App\Http\Controllers;

use App\Models\Gameweek;
use App\Models\Group;
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
     * @param Request $request
     * @param Group $group
     * @return RedirectResponse
     */
    public function store(Request $request, Group $group): RedirectResponse
    {
        // Validate
        $this->validate($request, [
            'name' => [
                'nullable', 'string',
            ],
            'start_date' => [
                'required', 'date',
            ],
            'end_date' => [
                'required', 'date',
            ],
            'description' => [
                'nullable', 'string',
            ],
        ]);

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
        return view('gameweeks.show', compact('group', 'gameweek'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Gameweek $gameweek
     * @return Response
     */
    public function edit(Gameweek $gameweek)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Gameweek $gameweek
     * @return Response
     */
    public function update(Request $request, Gameweek $gameweek)
    {
        //
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
