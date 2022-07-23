<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends Model
{
    use GeneratesUuidOnCreation;
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($group) {
            // Ensure new models have a valid key when created
            if (empty($group->key)) {
                $group->key = self::generateValidKey();
            }
        });
    }

    public static function generateValidKey()
    {
        $key = null;

        while (is_null($key) || self::query()->where('key', $key)->exists()) {
            $key = Str::random(10);
        }

        return $key;
    }
}
