<?php

namespace App\Services\ApiFootball;

use App\Services\Concerns\HasFake;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Client
{
    use HasFake;

    public function __construct(
        protected string $baseUrl,
        protected string $apiKey,
        protected int $timeout = 10,
        protected null|int $retryTimes = null,
        protected null|int $retryMilliseconds = null,
    ) {}

    /**
     * Creates a PendingRequest object with the configured settings.
     *
     * @return PendingRequest
     */
    public function createRequest(): PendingRequest
    {
        $request = Http::withHeaders([
            'x-apisports-key' => $this->apiKey
        ])->timeout(
            seconds: $this->timeout
        );

        if (! is_null($this->retryTimes) && ! is_null($this->retryMilliseconds)) {
            $request->retry(
                times: $this->retryTimes,
                sleepMilliseconds: $this->retryMilliseconds
            );
        }

        return $request;
    }

    public function getLeagues()
    {
        $request = $this->createRequest();

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

        $request = $this->createRequest();

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