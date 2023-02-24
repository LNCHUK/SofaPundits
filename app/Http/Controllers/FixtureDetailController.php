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
        $homeColour = $fixture->lineups()->where('team_id', $fixture->home_team_id)->first()->colours['player']['primary'];
        $awayColour = $fixture->lineups()->where('team_id', $fixture->away_team_id)->first()->colours['player']['primary'];

        return view('fixtures.detail', [
            'fixture' => $fixture,
            'homeColour' => $homeColour,
            'awayColour' => $awayColour,
            'homeStats' => $fixture->statistics()->where('team_id', $fixture->home_team_id)->first(),
            'awayStats' => $fixture->statistics()->where('team_id', $fixture->away_team_id)->first(),
        ]);
    }
}
