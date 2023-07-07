<?php

namespace App\Concerns\Services;

use Illuminate\Http\Client\Response;

interface ScoutResponse
{
    public static function fromHttpResponse(Response $response): static;
}