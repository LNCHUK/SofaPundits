<?php

namespace App\Models\ApiFootball;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'league_season_id',
        'referee',
        'timezone',
        'date',
        'timestamp',
        'kick_off',
        'periods',
        'home_team_id',
        'away_team_id',
        'venue',
        'venue_city',
        'status',
        'status_code',
        'time_elapsed',
        'round',
        'goals',
        'score',
    ];

    protected $casts = [
        'periods' => 'array',
        'goals' => 'array',
        'score' => 'array',
    ];
}
