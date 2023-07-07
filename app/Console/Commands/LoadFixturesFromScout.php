<?php

namespace App\Console\Commands;

use App\Concerns\Services\ScoutResponse;
use App\Concerns\Services\SofaPunditsScout;
use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\Team;
use App\Services\Scout\Response;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesFromScout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:load-fixtures {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves fixtures from the Scout app';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(SofaPunditsScout $client)
    {
        // Check from and to date (if set)
        $fromDate = $this->option('from') ?? now()->format('Y-m-d');
        $toDate = $this->option('to');

        $page = 1;
        $perPage = 100;
        $fixtures = [];

        $progressBar = $this->output->createProgressBar();

        $message = 'Loading fixtures from Scout'
            . ($fromDate ? ' starting from ' . $fromDate : '')
            . ($toDate ? ' until ' . $toDate : '');
        $this->info($message . "\n");

        while (true) {
            /** @var Response $response */
            $response = $client->getFixtures([
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'per_page' => $perPage,
                'page' => $page
            ]);

            if (!$response->isWasSuccessful()) {
                $this->error('Error loading fixtures. Status: '. $response->getStatus());
                $this->error($response->getException()->getMessage());
                break;
            }

            $results = $response->getDecodedBody();

            if ($page == 1) {
                $this->info("Found {$results['meta']['total']} results\n");
                $progressBar->setMaxSteps($results['meta']['total']);
                $progressBar->start();
            }

            $numberOfResults = ($results['meta']['to'] - $results['meta']['from']) + 1;
            $progressBar->advance($numberOfResults);

            $fixtures = array_merge($fixtures, $results['data']);

            if ($results['meta']['to'] >= $results['meta']['total']) {
                // At the end of the results, we can stop here
                break;
            }

            $page++;
        }

        $progressBar->finish();
        $this->info("\n\nResults loaded successfully, processing fixtures\n");

        $errors = [];

        $this->withProgressBar($fixtures, function (array $fixture) use (&$errors) {
            try {
                $homeTeam = Team::updateOrCreate([
                    'id' => $fixture['home_team']['id']
                ], Arr::only($fixture['home_team'], ['name', 'logo']));

                $awayTeam = Team::updateOrCreate([
                    'id' => $fixture['away_team']['id']
                ], Arr::only($fixture['away_team'], ['name', 'logo']));

                Fixture::query()->updateOrCreate([
                    'id' => $fixture['id'],
                ], [
                    'league_season_id' => $fixture['season_id'],
                    'referee' => $fixture['referee'],
                    'timezone' => 'UTC',
                    'date' => $fixture['kick_off'],
                    'timestamp' => Carbon::parse($fixture['kick_off'])->timestamp,
                    'kick_off' => $fixture['kick_off'],
                    'periods' => ['first' => null, 'second' => null],
                    'home_team_id' => $fixture['home_team']['id'],
                    'away_team_id' => $fixture['away_team']['id'],
                    'venue' => $fixture['venue']['name'],
                    'venue_city' => $fixture['venue']['city'],
                    'status' => $fixture['status']['name'],
                    'status_code' => $fixture['status']['code'],
                    'time_elapsed' => $fixture['time_elapsed'],
                    'round' => $fixture['round'],
                    'home_goals' => $homeGoals = $fixture['score']['latest']['home'],
                    'away_goals' => $awayGoals = $fixture['score']['latest']['away'],
                    'goals' => [
                        'home' => $homeGoals,
                        'away' => $awayGoals,
                    ],
                    'score' => [
                        'penalty' => $fixture['score']['after_penalties'],
                        'fulltime' => $fixture['score']['full_time'],
                        'halftime' => $fixture['score']['half_time'],
                        'extratime' => $fixture['score']['extra_time'],
                        'latest' => $fixture['score']['latest'],
                    ],
                ]);
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
            }
        });

        $this->info("\n\nProcessed with " . count($errors) . " errors\n");

        foreach ($errors as $error) {
            $this->info("$error\n");
        }

        return Command::SUCCESS;
    }
}
