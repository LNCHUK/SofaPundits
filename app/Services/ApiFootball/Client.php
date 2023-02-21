<?php

namespace App\Services\ApiFootball;

use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\LeagueSeason;
use App\Models\ApiFootball\Team;
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

    public function getFixtures(LeagueSeason $leagueSeason = null)
    {
        $request = $this->createRequest();

        $response = $request->get(
            url: $this->baseUrl . '/fixtures',
            query: [
                'league' => $leagueSeason->league->id,
                'season' => $leagueSeason->year,
            ]
        );

        if (! $response->successful()) {
            return $response->toException();
        }

        return $response;
    }

    /**
     * @param LeagueSeason $leagueSeason
     * @param Team $team
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\RequestException|\Illuminate\Http\Client\Response|null
     */
    public function getTeamStatistics(LeagueSeason $leagueSeason, Team $team)
    {
        $request = $this->createRequest();

        $response = $request->get(
            url: $this->baseUrl . '/teams/statistics',
            query: [
                'league' => $leagueSeason->league->id,
                'season' => $leagueSeason->year,
                'team' => $team->id,
                'date' => null,
            ]
        );

        if (! $response->successful()) {
            return $response->toException();
        }

        return $response;
    }

    public function getFixtureEvents(Fixture $fixture)
    {
        $request = $this->createRequest();

        $response = $request->get(
            url: $this->baseUrl . '/fixtures/events',
            query: [
                'fixture' => $fixture->id,
                'team' => null, // Possible, but unused, parameter
                'player' => null, // Possible, but unused, parameter
                'type' => null, // Possible, but unused, parameter
            ]
        );

        if (! $response->successful()) {
            return $response->toException();
        }

        return $response;
    }

    public function getFixtureStatistics(Fixture $fixture)
    {
        $request = $this->createRequest();

        $response = $request->get(
            url: $this->baseUrl . '/fixtures/statistics',
            query: [
                'fixture' => $fixture->id,
                'team' => null, // Possible, but unused, parameter
                'type' => null, // Possible, but unused, parameter
            ]
        );

        if (! $response->successful()) {
            return $response->toException();
        }

        return $response;
    }
}