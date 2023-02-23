<?php

namespace App\Jobs;

use App\Concerns\Jobs\CanBeRedispatched;
use App\Models\ApiFootball\Fixture;
use App\Models\FixtureStatistics;
use App\Services\ApiFootball\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportFixtureStatistics implements ShouldQueue
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
            $response = $this->apiFootball->getFixtureStatistics($this->fixture);

            foreach ($response->collect('response') as $fixtureStatistics) {
                $stats = collect($fixtureStatistics['statistics']);

                FixtureStatistics::updateOrCreate([
                    'fixture_id' => $this->fixture->id,
                    'team_id' => $fixtureStatistics['team']['id'],
                ], [
                    'shots_on_goal' => $stats->firstWhere('type', 'Shots on Goal')['value'] ?? 0,
                    'shots_off_goal' => $stats->firstWhere('type', 'Shots off Goal')['value'] ?? 0,
                    'total_shots' => $stats->firstWhere('type', 'Total Shots')['value'] ?? 0,
                    'blocked_shots' => $stats->firstWhere('type', 'Blocked Shots')['value'] ?? 0,
                    'shots_inside_box' => $stats->firstWhere('type', 'Shots insidebox')['value'] ?? 0,
                    'shots_outside_box' => $stats->firstWhere('type', 'Shots outsidebox')['value'] ?? 0,
                    'fouls' => $stats->firstWhere('type', 'Fouls')['value'] ?? 0,
                    'corners' => $stats->firstWhere('type', 'Corner Kicks')['value'] ?? 0,
                    'offsides' => $stats->firstWhere('type', 'Offsides')['value'] ?? 0,
                    'possession' => (float) rtrim($stats->firstWhere('type', 'Ball Possession')['value'] ?? 0, '%'),
                    'yellow_cards' => $stats->firstWhere('type', 'Yellow Cards')['value'] ?? 0,
                    'red_cards' => $stats->firstWhere('type', 'Red Cards')['value'] ?? 0,
                    'saves' => $stats->firstWhere('type', 'Goalkeeper Saves')['value'] ?? 0,
                    'total_passes' => $stats->firstWhere('type', 'Total passes')['value'] ?? 0,
                    'passes_completed' => $stats->firstWhere('type', 'Passes accurate')['value'] ?? 0,
                    'pass_accuracy' => (float) rtrim($stats->firstWhere('type', 'Passes %')['value'] ?? 0, '%'),
                    'expected_goals' => $stats->firstWhere('type', 'expected_goals')['value'] ?? 0,
                ]);
            }

            $this->redispatchJob();
        } catch (\Exception $ex) {
            report($ex);

            Log::channel('api-logs')->error("Error importing fixture statistics", [
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
