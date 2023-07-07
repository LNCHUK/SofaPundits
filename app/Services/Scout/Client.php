<?php

namespace App\Services\Scout;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Client
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $apiKey,
        protected int $timeout = 10,
        protected null|int $retryTimes = null,
        protected null|int $retryMilliseconds = null,
    ) {}

    private function createRequest(): PendingRequest
    {
        $request = Http::withHeaders([
            'X-SP-Scout-Api-Key' => $this->apiKey
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

    public function getFixtures($params = [])
    {
        $request = $this->createRequest();

        $response = $request->get(
            url: $this->baseUrl . 'fixtures',
            query: $params
        );

        // TODO: Update with response classes
        return $response->body();

        if (! $response->successful()) {
            return $response->toException();
        }

        return $response;
    }
}