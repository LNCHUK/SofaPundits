<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixtureStatistics extends Model
{
    protected $fillable = [
        'fixture_id',
        'team_id',
        'shots_on_goal',
        'shots_off_goal',
        'total_shots',
        'blocked_shots',
        'shots_inside_box',
        'shots_outside_box',
        'fouls',
        'corners',
        'offsides',
        'possession',
        'yellow_cards',
        'red_cards',
        'saves',
        'total_passes',
        'passes_completed',
        'pass_accuracy',
        'expected_goals',
    ];
}
