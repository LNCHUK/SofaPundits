<?php

namespace App\Http\Controllers;

use App\Http\Requests\Groups\CreateRequest;
use App\Models\Group;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest $request
     * @return RedirectResponse
     */
    public function store(CreateRequest $request): RedirectResponse
    {
        $group = Group::create($request->all());

        $group->users()->attach(auth()->id(), ['is_creator' => true]);

        return redirect()->route('groups.show', $group);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @return Renderable
     */
    public function show(Group $group): Renderable
    {
        $backedTeam = $group->getBackedTeamForUser();

        return view('groups.show', compact('group', 'backedTeam'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }

    /**
     * Joins the selected Group.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function join(Group $group)
    {
        $authedUser = auth()->user();

        if ($authedUser->groups->contains($group)) {
            return redirect()->back();
        }

        $group->users()->attach($authedUser->id);

        return redirect()->route('groups.show', $group);
    }
}
