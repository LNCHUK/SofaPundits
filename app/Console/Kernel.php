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
        // Pull down team information for any teams who had a game during the day
        // - Imports towards the end of the day to get results of the day's game
        $schedule->command('import:team-statistics')
            ->dailyAt('23:30');

        // Run the core import functions once per day (leagues, countries, league seasons, etc.)
        $schedule->command('import:leagues --trigger=schedule')
            ->dailyAt('01:00');

        // Set up the daily imports for fixture events, stats and lineups for the days fixtures
        $schedule->command('fixtures:prepare-daily-jobs')
            ->dailyAt('02:00');

        // Import fixtures frequently depending on the day
        $this->scheduleFixtureImports($schedule);
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
            ->between('17:00', '22:30');

        $schedule->command('import:fixtures')
            ->days([
                Schedule::SATURDAY,
                Schedule::SUNDAY,
            ])
            ->everyTwoMinutes()
            ->timezone('Europe/London')
            ->between('12:00', '22:30');

        $schedule->command('import:fixtures')
            ->days([
                Schedule::MONDAY,
            ])
            ->everyTwoMinutes()
            ->timezone('Europe/London')
            ->between('17:00', '22:30');

        // Update the fixtures each day in the morning
        $schedule->command('import:fixtures')
            ->dailyAt('03:00');
    }
}
