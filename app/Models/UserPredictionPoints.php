<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPredictionPoints extends Model
{
    protected $table = 'user_prediction_scores';

    public function userPrediction(): BelongsTo
    {
        return $this->belongsTo(UserPrediction::class, 'id', 'id');
    }
}
