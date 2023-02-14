<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\ApiFootball\Team;
use App\Models\BackedTeam;
use App\Models\Group;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlayersController extends Controller
{
    public function index(Group $group): Renderable
    {
        $this->authorize('update', $group);

        return view('groups.players.index', [
            'group' => $group,
            'teams' => Team::query()
                ->orderBy('name')
                ->get()
                ->mapWithKeys(fn (Team $team) => [$team->id => $team->name])
        ]);
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        $this->authorize('update', $group);

        // TODO: Validate
        collect($request->backed_team)
            ->filter(fn ($teamId) => $teamId !== null)
            ->each(function ($teamId, $userId) use ($group) {
                BackedTeam::updateOrCreate([
                    'group_id' => $group->id,
                    'user_id' => $userId
                ], [
                    'team_id' => $teamId
                ]);
            });

        return redirect()->back();
    }
}
