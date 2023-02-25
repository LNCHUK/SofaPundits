<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixtureEvent extends Model
{
    protected $fillable = [
        'fixture_id',
        'type',
        'detail',
        'comments',
        'minutes_elapsed',
        'extra_minutes_elapsed',
        'time',
        'team_id',
        'team',
        'player_id',
        'player_name',
        'player',
        'secondary_player_id',
        'secondary_player_name',
        'assist',
    ];

    protected $casts = [
        'time' => 'json',
        'team' => 'json',
        'player' => 'json',
        'assist' => 'json',
    ];
}
