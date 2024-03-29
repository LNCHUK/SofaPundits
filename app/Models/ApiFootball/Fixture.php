<?php

namespace App\Models\ApiFootball;

use App\Enums\FixtureStatusCode;
use App\Models\FixtureEvent;
use App\Models\FixtureLineup;
use App\Models\FixtureStatistics;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Fixture extends Model
{
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
     * @return HasMany
     */
    public function lineups(): HasMany
    {
        return $this->hasMany(FixtureLineup::class);
    }

    /**
     * @return HasMany
     */
    public function statistics(): HasMany
    {
        return $this->hasMany(FixtureStatistics::class);
    }

    /**
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(FixtureEvent::class)
            ->orderBy('minutes_elapsed', 'ASC')
            ->orderBy('extra_minutes_elapsed', 'ASC');
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

    /**
     * @return string
     */
    public function getKickOffTimeAttribute(): string
    {
        $date = $this->kick_off;

        return $date->format('H:i');
    }

    /**
     * @return bool
     */
    public function isInPlay(): bool
    {
        // If the fixture is not yet complete, dispatch another instance of this event in 5 minutes time
        $finishedStatuses = collect([
            FixtureStatusCode::MATCH_FINISHED,
            FixtureStatusCode::FINISHED_AFTER_EXTRA_TIME,
            FixtureStatusCode::FINISHED_AFTER_PENALTIES,
        ]);

        // Check the fixture is currently in play
        return now()->greaterThanOrEqualTo($this->kick_off)
            && (
                $finishedStatuses->doesntContain($this->status_code)
                || now()->isBefore($this->kick_off->addHours(2))
            );
    }
}
