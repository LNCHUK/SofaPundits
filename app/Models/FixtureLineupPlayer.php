<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixtureLineupPlayer extends Model
{
    protected $fillable = [
        'fixture_lineup_id',
        'type',
        'player_id',
        'name',
        'number',
        'position',
        'grid_position',
        'player',
    ];

    protected $casts = [
        'player' => 'array',
    ];
}
