<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use App\Models\ApiFootball\Fixture;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Gameweek extends Model
{
    use GeneratesUuidOnCreation;
    use HasFactory;

    protected $fillable = [
        'group_id',
        'name',
        'start_date',
        'end_date',
        'description',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * @return BelongsToMany
     */
    public function fixtures(): BelongsToMany
    {
        return $this->belongsToMany(Fixture::class, 'gameweek_fixtures');
    }

    /**
     * @return HasMany
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(UserPrediction::class);
    }

    /**
     * @return Collection
     */
    public function getFixturesGroupedByDate(): Collection
    {
        return $this->fixtures()
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('kick_off', 'ASC')
            ->get()
            ->groupBy(function (Fixture $fixture) {
                return $fixture->kick_off->format('l jS F Y');
            });
    }

    /**
     * @return HasMany
     */
    public function activeUserPredictions(): HasMany
    {
        return $this->predictions()
            ->where('user_id', auth()->id());
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeUpcoming(Builder $query): void
    {
        $query->where('start_date', '>', now());
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopePast(Builder $query): void
    {
        $query->where('end_date', '<', now());
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeActive(Builder $query): void
    {
        $query->where(function ($query) {
            $now = now();

            $query->where('start_date', '<=', $now)
                ->where('end_date', '>', $now);
        });
    }

    public function getUserPredictions($user = null)
    {
        $user = $user ?? auth()->user();

        return $this->predictions()
            ->where('user_id', $user->id)
            ->get();
    }

    public function getCssClassForFixturePrediction(Fixture $fixture, $user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        $prediction = $this->getUserPredictions($user)->firstWhere('fixture_id', $fixture->id);

        if ($prediction) {
            return optional($prediction->result)->cssClass();
        }

        return '';
    }

    public function getHomeScoreForFixturePrediction(Fixture $fixture, $user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        $prediction = $this->getUserPredictions($user)->firstWhere('fixture_id', $fixture->id);

        return $prediction ? $prediction->home_score : null;
    }

    public function getAwayScoreForFixturePrediction(Fixture $fixture, $user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        $prediction = $this->getUserPredictions($user)->firstWhere('fixture_id', $fixture->id);

        return $prediction ? $prediction->away_score : null;
    }
}
