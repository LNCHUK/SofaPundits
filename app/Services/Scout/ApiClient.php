<?php

namespace App\Services\Scout;

use App\Concerns\Services\ScoutResponse;
use App\Concerns\Services\SofaPunditsScout;
use Illuminate\Support\Facades\Http;

class ApiClient implements SofaPunditsScout
{
    /**
     * @var
     */
    private $request;

    public function __construct()
    {
        $config = config('services.sp_scout');

        $this->request = Http::withHeaders([
            'X-SP-Scout-Api-Key' => $config['api_key']
        ])->baseUrl($config['base_url'])
            ->timeout(
                seconds: $config['timeout']
            );

        if (! is_null($config['retry_times']) && ! is_null($config['retry_milliseconds'])) {
            $this->request->retry(
                times: $config['retry_times'],
                sleepMilliseconds: $config['retry_milliseconds']
            );
        }
    }

    public function getFixtures($params = []): ScoutResponse
    {
        // Make API call
        $response = $this->request->get(
            url: 'fixtures',
            query: $params
        );

        return Response::fromHttpResponse($response);
    }
}