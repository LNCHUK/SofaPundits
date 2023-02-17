<?php

namespace App\Models\ApiFootball;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStatistics extends Model
{
    protected $fillable = [
        'team_id',
        'league_season',
        'league_id',
        'form',
        'fixtures',
        'goals',
        'biggest',
        'clean_sheet',
        'failed_to_score',
        'penalty',
        'lineups',
        'cards',
    ];

    protected $casts = [
        'fixtures' => 'json',
        'goals' => 'json',
        'biggest' => 'json',
        'clean_sheet' => 'json',
        'failed_to_score' => 'json',
        'penalty' => 'json',
        'lineups' => 'json',
        'cards' => 'json',
    ];
}
