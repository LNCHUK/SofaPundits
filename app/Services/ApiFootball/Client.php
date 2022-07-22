<?php

namespace App\Services\ApiFootball;

use App\Services\Concerns\HasFake;
use Illuminate\Support\Facades\Http;

class Client
{
    use HasFake;

    public function __construct(
        protected string $baseUrl,
        protected string $apiKey
    ) {}

    public function leagues()
    {
        $request = Http::withHeaders([
            'x-apisports-key' => $this->apiKey
        ]);

        $response = $request->get(
            url: $this->baseUrl . '/leagues',
        );

        if (! $response->successful()) {
            return $response->toException();
        }

        return $response;
    }

    public function fixtures($date = null)
    {
        // If date is null, set to today
        $date = $date ?? now()->format('Y-m-d');

        $request = Http::withHeaders([
            'x-apisports-key' => $this->apiKey
        ]);

        $response = $request->get(
            url: $this->baseUrl . '/fixtures',
            query: ['date' => $date]
        );

        if (! $response->successful()) {
            return $response->toException();
        }

        return $response;
    }
}