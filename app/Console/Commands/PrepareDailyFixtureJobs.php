<?php

namespace App\Console\Commands;

use App\Jobs\ImportFixtureEvents;
use App\Models\ApiFootball\Fixture;
use Illuminate\Console\Command;

class PrepareDailyFixtureJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixtures:prepare-daily-jobs';

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
        // Get all fixtures for the day
        $dailyFixtures = Fixture::query()
            ->where('kick_off', 'LIKE', now()->format('Y-m-d%'))
            ->get();

        $dailyFixtures->each(function (Fixture $fixture) {
            // On kick off, start querying events every 5 minutes until the match is finished
            ImportFixtureEvents::dispatch($fixture->id)
                ->delay($fixture->kick_off);
        });



        // If not, dispatch a job to the queue that will create the record
            //

        return Command::SUCCESS;
    }
}
