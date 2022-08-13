<?php

namespace App\Models\ApiFootball;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
        'home_goals',
        'away_goals',
        'score',
    ];

    protected $casts = [
        'date' => 'datetime',
        'periods' => 'array',
        'goals' => 'array',
        'score' => 'array',
        'kick_off' => 'datetime',
    ];

    /**
     * @return HasOne
     */
    public function homeTeam(): HasOne
    {
        return $this->hasOne(Team::class, 'id', 'home_team_id');
    }
    /**
     * @return HasOne
     */
    public function awayTeam(): HasOne
    {
        return $this->hasOne(Team::class, 'id', 'away_team_id');
    }

    /**
     * @return BelongsTo
     */
    public function leagueSeason(): BelongsTo
    {
        return $this->belongsTo(LeagueSeason::class);
    }

    /**
     * @return HasOneThrough
     */
    public function league(): HasOneThrough
    {
        return $this->hasOneThrough(
            League::class,
            LeagueSeason::class,
            'id', // Foreign key on the league_seasons table...
            'id', // Foreign key on the leagues table...
            'league_season_id', // Local key on the fixtures table...
            'league_id' // Local key on the league_seasons table...
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->homeTeam->name
            . ' vs '
            . $this->awayTeam->name
            . ' (' . $this->kick_off->format('jS F Y, g:ia') . ')';
    }

    public function getKickOffTimeAttribute(): string
    {
        $date = $this->kick_off;

        if (now()->format('I') === "0") {
            $date->addHour();
        }

        return $date->format('H:i');
    }
}
