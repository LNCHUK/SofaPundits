<?php

namespace App\Console\Commands;

use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\TeamStatistics;
use App\Services\ApiFootball\Client;
use Illuminate\Console\Command;

class ImportTeamStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:team-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import statistics for all teams with a fixture in the current day';

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
        Fixture::query()
            ->with(['leagueSeason', 'homeTeam', 'awayTeam'])
            ->where('kick_off', 'LIKE', now()->format('Y-m-d%'))
            ->get()
            ->map(function (Fixture $fixture) {
                return [
                    $this->apiFootball->getTeamStatistics($fixture->leagueSeason, $fixture->homeTeam)->body(),
                    $this->apiFootball->getTeamStatistics($fixture->leagueSeason, $fixture->awayTeam)->body(),
                ];
            })
            ->flatten()
            ->each(function (string $encodedApiResponse) {
                $responseBody = json_decode($encodedApiResponse, true)['response'];
                TeamStatistics::updateOrCreate([
                    'team_id' => $responseBody['team']['id'],
                    'league_season_id' => $responseBody['league']['season'],
                ], [
                    'league_id' => $responseBody['league']['id'],
                    'form' => $responseBody['form'],
                    'fixtures' => $responseBody['fixtures'],
                    'goals' => $responseBody['goals'],
                    'biggest' => $responseBody['biggest'],
                    'clean_sheet' => $responseBody['clean_sheet'],
                    'failed_to_score' => $responseBody['failed_to_score'],
                    'penalty' => $responseBody['penalty'],
                    'lineups' => $responseBody['lineups'],
                    'cards' => $responseBody['cards'],
                ]);
            });

        return Command::SUCCESS;
    }
}
