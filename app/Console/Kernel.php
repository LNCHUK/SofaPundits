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
        // Run the import fixtures command every 15 minutes on the core game days
        // between the hours of 12pm (midday) and 10pm
        // COST: 40 API calls per day on the chosen days
        $schedule->command('import:fixtures')
            ->days([
                Schedule::FRIDAY,
                Schedule::SATURDAY,
                Schedule::SUNDAY,
                Schedule::MONDAY
            ])
            ->everyFifteenMinutes()
            ->timezone('Europe/London')
            ->between('12:00', '22:00');

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
