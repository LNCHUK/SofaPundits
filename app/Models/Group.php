<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Group extends Model
{
    use GeneratesUuidOnCreation;
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($group) {
            // Ensure new models have a valid key when created
            if (empty($group->key)) {
                $group->key = self::generateValidKey();
            }
        });
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->withPivot(['is_creator']);
    }

    /**
     * @return User
     */
    public function creator(): User
    {
        return $this->users()
            ->wherePivot('is_creator', true)
            ->first();
    }

    /**
     * @return HasMany
     */
    public function gameweeks(): HasMany
    {
        return $this->hasMany(Gameweek::class)
            ->when(
                auth()->user()->isCreatorOf($this) === false,
                fn ($query) => $query->whereNotNull('published_at')
            );
    }

    /**
     * @return HasMany
     */
    public function upcomingGameweeks(): HasMany
    {
        return $this->gameweeks()->upcoming();
    }

    /**
     * @return HasMany
     */
    public function activeGameweeks(): HasMany
    {
        return $this->gameweeks()->active();
    }

    /**
     * @return HasMany
     */
    public function pastGameweeks(): HasMany
    {
        return $this->gameweeks()->past();
    }

    /**
     * Generates a valid, unique, key to for a Group.
     *
     * @return string
     */
    public static function generateValidKey(): string
    {
        $key = null;

        while (is_null($key) || self::query()->where('key', $key)->exists()) {
            $key = Str::upper(Str::random(config('parameters.group_key_length')));
        }

        return $key;
    }

    /**
     * @return int
     */
    public function numberOfPlayers(): int
    {
        return count($this->users);
    }

    public function getLeagueTableData(): Collection
    {
        $position = 0;
        $previousPoints = null;
        $skipped = 0;

        return UserPredictionPoints::query()
            ->selectRaw("SUM(points) AS total_points, user_id")
            ->with('user')
            ->whereHas('gameweek', function ($query) {
                $query->where('group_id', $this->id);
            })
            ->groupBy('user_id')
            ->orderBy('total_points', 'DESC')
            ->get()
            ->map(function (UserPredictionPoints $userPredictionPoints) use (&$position, &$previousPoints, &$skipped) {
                if ($previousPoints === (int) $userPredictionPoints->total_points) {
                    // We have a tie, leave the position but increment the skip
                    $skipped++;
                } else {
                    $position += $skipped + 1;
                    $skipped = 0;
                }

                // Assign the points to use in the next iteration, to confirm a tie
                $previousPoints = (int) $userPredictionPoints->total_points;

                $userPredictionPoints->position = $position;

                return $userPredictionPoints;
            });
    }
}
