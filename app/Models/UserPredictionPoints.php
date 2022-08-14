<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPredictionPoints extends Model
{
    protected $table = 'user_prediction_scores';

    /**
     * @return BelongsTo
     */
    public function userPrediction(): BelongsTo
    {
        return $this->belongsTo(UserPrediction::class, 'id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function gameweek(): BelongsTo
    {
        return $this->belongsTo(Gameweek::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
