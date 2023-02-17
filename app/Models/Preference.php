<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Preference extends Model
{
    use GeneratesUuidOnCreation;
    use HasFactory;

    protected $fillable = [
        'slug',
        'type',
        'title',
        'category',
        'description',
        'default_value',
        'choices',
    ];

    protected $casts = [
        'choices' => 'array',
    ];

    /**
     * @return HasMany
     */
    public function userPreferences(): HasMany
    {
        return $this->hasMany(UserPreference::class);
    }
}
