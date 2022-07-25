<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    protected static function booted(): void
    {
        static::creating(function ($group) {
            // Ensure new models have a valid key when created
            if (empty($group->key)) {
                $group->key = self::generateValidKey();
            }
        });
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->withPivot(['is_creator']);
    }

    /**
     * Generates a valid, unique, key to for a Group.
     *
     * @return string
     */
    public static function generateValidKey(): string
    {
        $key = null;

        while (is_null($key) || self::query()->where('key', $key)->exists()) {
            $key = Str::upper(Str::random(10));
        }

        return $key;
    }

    public function numberOfPlayers(): int
    {
        return count($this->users);
    }
}
