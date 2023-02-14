<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use App\Enums\GameweekStatus;
use App\Models\ApiFootball\Fixture;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Gameweek extends Model
{
    use GeneratesUuidOnCreation;
    use HasFactory;

    protected $fillable = [
        'group_id',
        'name',
        'start_date',
        'end_date',
        'first_kick_off',
        'description',
        'status',
        'published_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'first_kick_off' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->resolveRouteBindingQuery($this, $value, $field)
            ->first();
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
        return $this->belongsToMany(Fixture::class, 'gameweek_fixtures')
            ->orderBy('kick_off', 'ASC');
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
        $query->where(function ($query) {
            $query->whereNotNull('first_kick_off')->where('first_kick_off', '>', now());
        })->orWhere(function ($query) {
            $query->whereNull('first_kick_off')->where('start_date', '>', now());
        });
    }

    /**
     * @return bool
     */
    public function isUpcoming(): bool
    {
        return ($this->first_kick_off ?? $this->start_date)->isAfter(now());
    }

    /**
     * @return Carbon|null
     */
    public function deadline(): ?Carbon
    {
        return ($this->first_kick_off ?? $this->start_date)->subHour();
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
     * @return bool
     */
    public function isPast(): bool
    {
        return $this->end_date->isBefore(now());
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeActive(Builder $query): void
    {
        $now = now();

        $query->where(function ($query) use ($now) {
            // If we have a first_kick_off set...
            $query->whereNotNull('first_kick_off')
                ->where('first_kick_off', '<', $now)
                ->where('end_date', '>', $now);
        })->orWhere(function ($query) use ($now) {
            // If not (legacy data or no fixtures added)
            $query->whereNull('first_kick_off')
                ->where('start_date', '<', $now)
                ->where('end_date', '>', $now);
        });
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $startDate = ($this->first_kick_off ?? $this->start_date);

        return $startDate->isBefore(now())
            && $this->end_date->isAfter(now());
    }

    /**
     * @return string
     */
    public function getStatusAttribute(): string
    {
        if ($this->isActive()) {
            return 'Active';
        } else if ($this->isPast()) {
            return 'Completed';
        } else if ($this->isUpcoming()) {
            return 'Not started';
        }

        return '';
    }

    /**
     * @param $user
     * @return Collection
     */
    public function getUserPredictions($user = null): Collection
    {
        $user = $user ?? auth()->user();

        return $this->predictions()
            ->where('user_id', $user->id)
            ->get();
    }

    public function getUserPredictionForFixture(User $user, Fixture $fixture): ?UserPrediction
    {
        return $this->predictions()
            ->where('user_id', $user->id)
            ->where('fixture_id', $fixture->id)
            ->first();
    }

    /**
     * Returns the appropriate CSS class for a prediction for a given Fixture and User.
     *
     * If no user is provided, the currently authenticated user is used.
     *
     * @param Fixture $fixture
     * @param $user
     * @return string
     */
    public function getCssClassForFixturePrediction(Fixture $fixture, $user = null): ?string
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

    /**
     * Returns the home score for a prediction for a given Fixture and User.
     *
     * If no user is provided, the currently authenticated user is used.
     *
     * @param Fixture $fixture
     * @param null $user
     * @return int|null
     */
    public function getHomeScoreForFixturePrediction(Fixture $fixture, $user = null): ?int
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        $prediction = $this->getUserPredictions($user)->firstWhere('fixture_id', $fixture->id);

        return $prediction ? $prediction->home_score : null;
    }

    /**
     * Returns the away score for a prediction for a given Fixture and User.
     *
     * If no user is provided, the currently authenticated user is used.
     *
     * @param Fixture $fixture
     * @param $user
     * @return int|null
     */
    public function getAwayScoreForFixturePrediction(Fixture $fixture, $user = null): ?int
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        $prediction = $this->getUserPredictions($user)->firstWhere('fixture_id', $fixture->id);

        return $prediction ? $prediction->away_score : null;
    }

    /**
     * Returns the total points for the active user for this gameweek.
     *
     * @return int
     */
    public function getPointsForActiveUser(): int
    {
        return $this->getPointsForUser(auth()->user());
    }

    /**
     * Returns the total points for the given user for this gameweek.
     *
     * @return int
     */
    public function getPointsForUser(User $user): int
    {
        return UserPredictionPoints::query()
            ->where('gameweek_id', $this->id)
            ->where('user_id', $user->id)
            ->sum('points');
    }

    /**
     * Returns true or false to state if the Gameweek is pending or not.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return empty($this->published_at);
    }

    /**
     * Returns true or false to state if the Gameweek is published or not.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    /**
     * @return mixed
     */
    public function getPlayersOrderedByPoints(): Collection
    {
        return $this->group
            ->users
            ->sortByDesc(function ($item) {
                return $this->getPointsForUser($item);
            });
    }
}
