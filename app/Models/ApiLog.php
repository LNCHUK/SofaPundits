<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use GeneratesUuidOnCreation;
    protected $fillable = [
        'uuid',
        'api_name',
        'endpoint',
        'request_params',
        'response_body',
        'started_at',
        'completed_at',
        'was_successful',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'was_successful' => 'boolean',
    ];
}
