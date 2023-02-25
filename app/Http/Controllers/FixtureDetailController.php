<?php

namespace App\Http\Controllers;

use App\Models\ApiFootball\Fixture;
use App\Models\FixtureEvent;
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

        if ($fixture->events) {
            $homeGoals = $fixture->events
                ->filter(function (FixtureEvent $fixtureEvent) use ($fixture) {
                    return $fixtureEvent->team_id === $fixture->home_team_id
                        && $fixtureEvent->type === 'Goal';
                })
                ->reduce(function ($carry, FixtureEvent $fixtureEvent) {
                    if (!$carry->has($fixtureEvent->player_name)) {
                        $carry->put($fixtureEvent->player_name, collect());
                    }

                    $carry->get($fixtureEvent->player_name)->push($fixtureEvent->minutes_elapsed);

                    return $carry;
                }, collect())
                ->mapWithKeys(function ($minutes, $player) {
                    return [$player => implode(', ', $minutes->toArray())];
                })
                ->reduce(function ($goalsStrings, $goals, $player) {
                    $goalsStrings[] = $player . ' (' . $goals . ')';
                    return $goalsStrings;
                }, []);

            $awayGoals = $fixture->events
                ->filter(function (FixtureEvent $fixtureEvent) use ($fixture) {
                    return $fixtureEvent->team_id === $fixture->away_team_id
                        && $fixtureEvent->type === 'Goal';
                })
                ->reduce(function ($carry, FixtureEvent $fixtureEvent) {
                    if (!$carry->has($fixtureEvent->player_name)) {
                        $carry->put($fixtureEvent->player_name, collect());
                    }

                    $carry->get($fixtureEvent->player_name)->push($fixtureEvent->minutes_elapsed);

                    return $carry;
                }, collect())
                ->mapWithKeys(function ($minutes, $player) {
                    return [$player => implode(', ', $minutes->toArray())];
                })
                ->reduce(function ($goalsStrings, $goals, $player) {
                    $goalsStrings[] = $player . ' (' . $goals . ')';
                    return $goalsStrings;
                }, []);
        } else {
            $homeGoals = [];
            $awayGoals = [];
        }

        return view('fixtures.detail', [
            'fixture' => $fixture,
            'homeLineup' => $homeLineup,
            'awayLineup' => $awayLineup,
            'homeColour' => $homeColour,
            'awayColour' => $awayColour,
            'homeStats' => $fixture->statistics()->where('team_id', $fixture->home_team_id)->first(),
            'awayStats' => $fixture->statistics()->where('team_id', $fixture->away_team_id)->first(),
            'homeGoals' => $homeGoals,
            'awayGoals' => $awayGoals,
        ]);
    }
}
