<?php

namespace App\Console\Commands;

use App\Jobs\UpdateTeamStatistics;
use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\LeagueSeason;
use Illuminate\Console\Command;

class LoadAllTeamStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teams:load-all-statistics {--s|season=} {--d|days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads statistics for all Teams in a given season';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $season = $this->option('season') ?? now()->format('Y');

        $startDate = now()->subDays($this->option('days'))->startOfDay()->format('Y-m-d H:i:s');

        // Get all fixtures across all leagues, where the league season matches the given season
        Fixture::query()
            ->whereHas('leagueSeason', function ($query) use ($season) {
                $query->where('year', $season);
            })
            ->where('kick_off', '>=', $startDate)
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
