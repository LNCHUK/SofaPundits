<?php

namespace App\Models\ApiFootball;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'logo',
    ];

    public function fixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'home_team_id', 'id')
            ->orWhere('away_team_id', $this->id);
    }
}
