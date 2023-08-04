<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_users')
            ->withPivot(['is_creator']);
    }

    /**
     * @return BelongsToMany
     */
    public function activeGroups(): BelongsToMany
    {
        return $this->groups()
            ->whereNull('completed_at');
    }

    /**
     * @return HasMany
     */
    public function backedTeams(): HasMany
    {
        return $this->hasMany(BackedTeam::class);
    }

    /**
     * @return bool
     */
    public function isInAtLeastOneGroup(): bool
    {
        return count($this->groups) > 0;
    }

    /**
     * @return HasMany
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(UserPrediction::class);
    }

    /**
     * @param Gameweek $gameweek
     * @return Collection
     */
    public function gameweekPredictions(Gameweek $gameweek): Collection
    {
        return $this->predictions()
            ->where('gameweek_id', $gameweek->id)
            ->get();
    }

    /**
     * @param Group $group
     * @return bool
     */
    public function isCreatorOf(Group $group): bool
    {
        return $this->is($group->creator());
    }


    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @param Gameweek $gameweek
     * @return bool
     */
    public function hasSavedPredictionsForAllFixturesInGameweek(Gameweek $gameweek): bool
    {
        return count($this->gameweekPredictions($gameweek)) === count($gameweek->fixtures);
    }

    /**
     * @return Collection
     */
    public function getPreferencesUngrouped(): Collection
    {
        return Preference::query()
            ->with(['userPreferences' => function ($query) {
                $query->where('user_id', $this->id);
            }])
            ->get()
            ->each(function (Preference $preference) {
                // Append the users selected value for this preference
                $preference->user_selected_value = optional($preference->userPreferences()->first())->value;
            });
    }

    /**
     * @return Collection
     */
    public function getPreferences(): Collection
    {
        // Get all preferences from the DB
        return cache()->rememberForever('user_preferences-' . $this->id, function () {
            return $this->getPreferencesUngrouped()
                ->groupBy(function (Preference $preference) {
                    return str($preference->category)->replace('-', ' ')->title();
                });
        });
    }
}
