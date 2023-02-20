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
        // Run the core import functions once per day
        $schedule->command('import:leagues --trigger=schedule')
            ->dailyAt('01:00');

        // Pull down team information for any teams who had a game during the day
        // - Imports towards the end of the day to get results of the day's game
        $schedule->command('import:team-statistics')
            ->dailyAt('23:30');

        // Import fixtures depending on the day
        $this->scheduleFixtureImports($schedule);

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

    private function scheduleFixtureImports(Schedule &$schedule)
    {
        // Run the import fixtures command every 15 minutes on the core game days
        // between the hours of 12pm (midday) and 10pm
        // COST: 40 API calls per day on the chosen days
        $schedule->command('import:fixtures')
            ->days([
                Schedule::FRIDAY,
            ])
            ->everyTwoMinutes()
            ->timezone('Europe/London')
            ->between('17:00', '22:00');

        $schedule->command('import:fixtures')
            ->days([
                Schedule::SATURDAY,
                Schedule::SUNDAY,
            ])
            ->everyTwoMinutes()
            ->timezone('Europe/London')
            ->between('12:00', '22:00');

        $schedule->command('import:fixtures')
            ->days([
                Schedule::MONDAY,
            ])
            ->everyTwoMinutes()
            ->timezone('Europe/London')
            ->between('17:00', '22:00');

        // Update the fixtures each day in the morning
        $schedule->command('import:fixtures')
            ->dailyAt('03:00');
    }
}
