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

        $now = now();
        $day = (clone $now)->format('N');
        $hour = (clone $now)->format('G');

        if (in_array($day, [5, 6, 7, 1]) && $hour >= 12 && $hour <=20) {
            // We are on a Friday, Saturday, Sunday or Monday between 12 and 8pm
            $schedule->command('import:fixtures')->everyTenMinutes();
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
