<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * @return HasMany
     */
    public function players(): HasMany
    {
        return $this->hasMany(FixtureLineupPlayer::class, 'fixture_lineup_id', 'id');
    }
}
