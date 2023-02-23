<?php

namespace App\Jobs;

use App\Concerns\Jobs\CanBeRedispatched;
use App\Models\ApiFootball\Fixture;
use App\Models\FixtureLineup;
use App\Models\FixtureLineupPlayer;
use App\Models\FixtureStatistics;
use App\Services\ApiFootball\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportFixtureLineups implements ShouldQueue
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
            $response = $this->apiFootball->getFixtureLineups($this->fixture);

            foreach ($response->collect('response') as $fixtureLineup) {
                $lineup = FixtureLineup::query()->updateOrCreate([
                    'fixture_id' => $this->fixture->id,
                    'team_id' => $fixtureLineup['team']['id'],
                ], [
                    'colours' => $fixtureLineup['team']['colors'],
                    'coach_id' => $fixtureLineup['coach']['id'],
                    'coach' => $fixtureLineup['coach'],
                    'formation' => $fixtureLineup['formation'],
                ]);

                collect($fixtureLineup['startXI'])
                    ->map(function (array $player) {
                        $player['type'] = 'starting_xi';
                        return $player;
                    })
                    ->merge(
                        collect($fixtureLineup['substitutes'])
                            ->map(function (array $player) {
                                $player['type'] = 'substitute';
                                return $player;
                            })
                    )
                    ->each(function (array $player) use ($lineup) {
                        FixtureLineupPlayer::updateOrCreate([
                            'fixture_lineup_id' => $lineup->id,
                            'player_id' => $player['player']['id'],
                        ], [
                            'type' => $player['type'],
                            'name' => $player['player']['name'],
                            'number' => $player['player']['number'],
                            'position' => $player['player']['pos'],
                            'grid_position' => $player['player']['grid'],
                            'player' => $player['player'],
                        ]);
                    });
            }

            $this->redispatchJob();
        } catch (\Exception $ex) {
            report($ex);

            Log::channel('api-logs')->error("Error importing fixture lineups", [
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
        return false;
    }

    /**
     * @return int
     */
    protected function redispatchFrequency(): int
    {
        return 5;
    }
}
