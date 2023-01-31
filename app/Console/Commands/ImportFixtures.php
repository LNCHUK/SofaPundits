<?php

namespace App\Console\Commands;

use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\LeagueSeason;
use App\Models\ApiFootball\Team;
use App\Services\ApiFootball\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportFixtures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:fixtures
                            {--season= : The season to import fixtures from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports fixtures from all selected leagues for the current season';

    public function __construct(
        private Client $apiFootball
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::channel('api-logs')->debug('import:fixtures - Starting import');

        $selectedSeason = $this->option('season');

        $leagues = $this->getLeaguesInUse();

        foreach ($leagues as $league) {
            $leagueSeason = LeagueSeason::query()
                ->whereHas('league',function ($query) use ($league) {
                    $query->where('name', $league['name'])
                        ->whereHas('country', function ($query) use ($league) {
                            $query->where('name', $league['country']);
                        });
                })
                // TODO: Set up a way to import previous seasons
                ->where('is_current', true)
                ->first();

            $fixturesResponse = $leaguesResponse = $this->apiFootball->getFixtures(
                leagueSeason: $leagueSeason
            );

            $fixtures = collect();
            foreach ($fixturesResponse->collect('response') as $item) {
                $fixtures->add(
                    $item,
                );
            }

            foreach ($fixtures as $fixture) {
                // Create or update the team records
                $homeTeam = Team::updateOrCreate([
                    'id' => $fixture['teams']['home']['id']
                ], $fixture['teams']['home']);

                $awayTeam = Team::updateOrCreate([
                    'id' => $fixture['teams']['away']['id']
                ], $fixture['teams']['away']);

                // Create the fixture record
                $fixture = Fixture::updateOrCreate([
                    'id' => $fixture['fixture']['id']
                ], [
                    'league_season_id' => $leagueSeason->id,
                    'referee' => $fixture['fixture']['referee'],
                    'timezone' => $fixture['fixture']['timezone'],
                    'date' => $fixture['fixture']['date'],
                    'timestamp' => $fixture['fixture']['timestamp'],
                    'kick_off' => Carbon::createFromTimestamp($fixture['fixture']['timestamp']),
                    'periods' => $fixture['fixture']['periods'],
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'venue' => $fixture['fixture']['venue']['name'],
                    'venue_city' => $fixture['fixture']['venue']['city'],
                    'status' => $fixture['fixture']['status']['long'],
                    'status_code' => $fixture['fixture']['status']['short'],
                    'time_elapsed' => $fixture['fixture']['status']['elapsed'],
                    'round' => $fixture['league']['round'],
                    'goals' => $fixture['goals'],
                    'home_goals' => $fixture['goals']['home'],
                    'away_goals' => $fixture['goals']['away'],
                    'score' => $fixture['score'],
                ]);
            }
        }

        Log::channel('api-logs')->debug('import:fixtures - Import complete');
    }

    private function getLeaguesInUse(): array
    {
        return config('leagues.chosen-leagues');
    }
}
