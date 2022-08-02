<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gameweek extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'name',
        'start_date',
        'end_date',
        'description',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
