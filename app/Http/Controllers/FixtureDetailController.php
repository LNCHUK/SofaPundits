<?php

namespace App\Http\Controllers;

use App\Models\ApiFootball\Fixture;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class FixtureDetailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Fixture $fixture): Renderable
    {
        $homeLineup = $fixture->lineups()->where('team_id', $fixture->home_team_id)->first();
        $homeColour = $homeLineup ? $homeLineup->colours['player']['primary'] : null;

        $awayLineup = $fixture->lineups()->where('team_id', $fixture->away_team_id)->first();
        $awayColour = $awayLineup ? $awayLineup->colours['player']['primary'] : null;

        return view('fixtures.detail', [
            'fixture' => $fixture,
            'homeLineup' => $homeLineup,
            'awayLineup' => $awayLineup,
            'homeColour' => $homeColour,
            'awayColour' => $awayColour,
            'homeStats' => $fixture->statistics()->where('team_id', $fixture->home_team_id)->first(),
            'awayStats' => $fixture->statistics()->where('team_id', $fixture->away_team_id)->first(),
        ]);
    }
}
