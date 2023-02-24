<?php

namespace App\Jobs;

use App\Concerns\Jobs\CanBeRedispatched;
use App\Models\ApiFootball\Fixture;
use App\Models\FixtureEvents;
use App\Services\ApiFootball\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportFixtureEvents implements ShouldQueue
{
    use CanBeRedispatched;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var Fixture
     */
    private $fixture;

    /**
     * @var Client
     */
    private $apiFootball;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $fixtureId)
    {
        $this->fixture = Fixture::findOrFail($fixtureId);

        $this->apiFootball = resolve(Client::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Call the API
        try {
            $response = $this->apiFootball->getFixtureEvents($this->fixture);

            DB::beginTransaction();

            // Wipe all events (the API likes to update events during the game)
            $this->fixture->events()->delete();

            foreach ($response->collect('response') as $fixtureEvent) {
                FixtureEvents::updateOrCreate([
                    'fixture_id' => $this->fixture->id,
                    'team_id' => $fixtureEvent['team']['id'],
                    'type' => $fixtureEvent['type'],
                ], [
                    'minutes_elapsed' => $fixtureEvent['time']['elapsed'],
                    'detail' => $fixtureEvent['detail'],
                    'comments' => $fixtureEvent['comments'],
                    'extra_minutes_elapsed' => $fixtureEvent['time']['extra'],
                    'time' => $fixtureEvent['time'],
                    'team' => $fixtureEvent['team'],
                    'player_id' => $fixtureEvent['player']['id'],
                    'player_name' => $fixtureEvent['player']['name'] ?? ' ',
                    'player' => $fixtureEvent['player'] ?? ' ',
                    'secondary_player_id' => $fixtureEvent['assist']['id'],
                    'secondary_player_name' => $fixtureEvent['assist']['name'],
                    'assist' => $fixtureEvent['assist'],
                ]);
            }

            DB::commit();

            $this->redispatchJob();
        } catch (\Exception $ex) {
            report($ex);

            DB::rollBack();

            Log::channel('api-logs')->error("Error importing fixture events", [
                'exception' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'stack_trace' => $ex->getTraceAsString(),
                'fixture' => $this->fixture,
                'api_response' => $response ?? '',
            ]);
        }
    }

    /**
     * @return bool
     */
    protected function shouldRedispatch(): bool
    {
        return $this->fixture->isInPlay();
    }

    /**
     * @return int
     */
    protected function redispatchFrequency(): int
    {
        return 5;
    }
}
