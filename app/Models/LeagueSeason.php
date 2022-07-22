<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
