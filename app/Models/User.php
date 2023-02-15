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
    use HasApiTokens, HasFactory, Notifiable;

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

    public function hasSavedPredictionsForAllFixturesInGameweek(Gameweek $gameweek)
    {
        return count($this->gameweekPredictions($gameweek)) === count($gameweek->fixtures);
    }
}
