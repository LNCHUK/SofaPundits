<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixtureLineup extends Model
{
    protected $fillable = [
        'fixture_id',
        'team_id',
        'colours',
        'coach_id',
        'coach',
        'formation',
    ];

    protected $casts = [
        'colours' => 'array',
        'coach' => 'array',
    ];
}
