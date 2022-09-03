<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        for ($hour = 12; $hour <= 20; $hour++) {
            for ($minutes = 0; $minutes < 60; $minutes += 10) {
                $schedule->command('import:fixtures')
                    ->fridays()->at($hour.':'.$minutes);
                $schedule->command('import:fixtures')
                    ->saturdays()->at($hour.':'.$minutes);
                $schedule->command('import:fixtures')
                    ->sundays()->at($hour.':'.$minutes);
                $schedule->command('import:fixtures')
                    ->mondays()->at($hour.':'.$minutes);
            }
        }

        // OPTION 1
        // Get all leagues
        // Filter by country and then for chosen leagues
            // Get fixtures

        // OPTION 2
        // Get all countries (once a year?)
            // Get all leagues for the chosen countries (same time as countries)
                // Get all seasons for each league (same time as above)
                // Get all teams for each league

        // Team statistics? Nice to have for the future potentially

        // Get league standings? Nice to have, allow us to show league position in gameweeks

        // Fixture rounds, get all for each selected season
        //
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
