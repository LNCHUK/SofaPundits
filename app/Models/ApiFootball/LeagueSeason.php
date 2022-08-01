<?php

namespace App\Models\ApiFootball;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeagueSeason extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'year',
        'start',
        'end',
        'is_current',
        'coverage',
    ];

    protected $casts = [
        'coverage' => 'array',
    ];

    protected $dates = [
        'start',
        'end',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }
}
