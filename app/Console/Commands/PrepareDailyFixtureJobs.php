<?php

namespace App\Console\Commands;

use App\Jobs\ImportFixtureEvents;
use App\Jobs\ImportFixtureLineups;
use App\Jobs\ImportFixtureStatistics;
use App\Models\ApiFootball\Fixture;
use Illuminate\Console\Command;

class PrepareDailyFixtureJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixtures:prepare-daily-jobs {--f|fixture=} {--d|date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches a jobs to retrieve statistics, events and lineups for daily fixtures';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fixtureId = $this->option('fixture');
        $date = $this->option('date') ?? now()->format('Y-m-d');

        $this->info('Importing for ' . $date);

        // Get all fixtures for the day
        $dailyFixtures = Fixture::query()
            ->when($fixtureId, fn ($query) => $query->where('id', $fixtureId))
            ->when(!$fixtureId, fn ($query) => $query->where('kick_off', 'LIKE', $date.'%'))
            ->get();

        $this->info(count($dailyFixtures) . ' fixtures found, dispatching relevant jobs');

        $dailyFixtures->each(function (Fixture $fixture) {
            // On kick off, start querying events every 5 minutes until the match is finished
            ImportFixtureEvents::dispatch($fixture->id)
                ->delay($fixture->kick_off);

            ImportFixtureStatistics::dispatch($fixture->id)
                ->delay($fixture->kick_off);

            ImportFixtureLineups::dispatch($fixture->id)
                ->delay($fixture->kick_off->subMinutes(20));
        });

        return Command::SUCCESS;
    }
}
