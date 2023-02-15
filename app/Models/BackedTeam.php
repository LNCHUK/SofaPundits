<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use App\Models\ApiFootball\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BackedTeam extends Model
{
    use GeneratesUuidOnCreation;
    protected $fillable = [
        'uuid',
        'user_id',
        'group_id',
        'team_id',
    ];

    /**
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return HasOne
     */
    public function leaderboardData(): HasOne
    {
        return $this->hasOne(BackedTeamResults::class, 'team_id', 'team_id')
            ->where('group_id', $this->group_id)
            ->where('user_id', $this->user_id);
    }
}
