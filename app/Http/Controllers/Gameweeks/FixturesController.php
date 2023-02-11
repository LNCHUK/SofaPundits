<?php

namespace App\Http\Controllers\Gameweeks;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPredictions\UpdateRequest as UpdatePredictionsRequest;
use App\Models\Gameweek;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
            $gameweek->fixtures()->sync($request->selected_fixtures);

            $earliestKickOff = collect($request->kick_off_times)
                ->map(fn ($dateString) => Carbon::parse($dateString))
                ->reduce(
                    fn (Carbon $currentDate, Carbon $dateBeingTested) => $dateBeingTested->isBefore($currentDate)
                        ? $dateBeingTested
                        : $currentDate,
                    now()->addYears(100)
                )->format('Y-m-d H:i:s');

            $gameweek->update(['first_kick_off' => $earliestKickOff]);
        } catch (\Exception $ex) {
            report($ex);
            DB::rollBack();
            // TODO: Report some sort of error
        }

        DB::commit();

        return redirect()->route('gameweeks.show', ['group' => $group, 'gameweek' => $gameweek]);
    }
}
