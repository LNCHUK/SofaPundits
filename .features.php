<?php

use Illuminate\Contracts\Foundation\Application;

/**
 * @returns array<string, bool>
 */
return static function (Application $app): array {
    return [
        // 'my.feature.flag' => true,
        'new-group-panels' => env('FEATURE_FLAG_NEW_GROUP_PANELS', false)
    ];
};
