<?php

namespace App\Concerns;

use Illuminate\Support\Str;

trait GeneratesUuidOnCreation
{
    public static function bootGeneratesUuidOnCreation()
    {
        static::creating(function ($group) {
            // Ensure new models have a UUID when created
            if (empty($group->uuid)) {
                $group->uuid = Str::uuid();
            }
        });
    }
}