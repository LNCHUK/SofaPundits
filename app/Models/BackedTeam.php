<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use App\Models\ApiFootball\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
