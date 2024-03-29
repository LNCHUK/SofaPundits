<?php

namespace App\Console\Commands;

use App\Jobs\UpdateTeamStatistics;
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
            ->each(function (Fixture $fixture) {
                UpdateTeamStatistics::dispatch(
                    $fixture->homeTeam->id,
                    $fixture->leagueSeason->id
                );

                UpdateTeamStatistics::dispatch(
                    $fixture->awayTeam->id,
                    $fixture->leagueSeason->id
                );
            });

        return Command::SUCCESS;
    }
}
