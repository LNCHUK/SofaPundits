<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'api_football' => [
        'base_url' => env('API_FOOTBALL_BASE_URL', 'https://v3.football.api-sports.io/'),
        'api_key' => env('API_FOOTBALL_API_KEY', ''),
        'timeout' => env('API_FOOTBALL_TIMEOUT', 10),
        'retry_times' => env('API_FOOTBALL_RETRY_TIMES', null),
        'retry_milliseconds' => env('API_FOOTBALL_RETRY_MILLISECONDS', null),
    ],

    'sp_scout' => [
        'base_url' => env('SP_SCOUT_BASE_URL', 'https://scout.sofapundits.uk/api/v1/'),
        'api_key' => env('SP_SCOUT_API_KEY'),
        'timeout' => env('SP_SCOUT_TIMEOUT', 10),
        'retry_times' => env('SP_SCOUT_RETRY_TIMES'),
        'retry_milliseconds' => env('SP_SCOUT_RETRY_MILLISECONDS'),
    ] ,

];
