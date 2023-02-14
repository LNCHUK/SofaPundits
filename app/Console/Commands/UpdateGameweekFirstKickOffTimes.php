<?php

namespace App\Console\Commands;

use App\Models\Gameweek;
use Illuminate\Console\Command;

class UpdateGameweekFirstKickOffTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gameweeks:update-kick-offs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the first_kick_off time for all Gameweeks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Gameweek::query()
            ->whereNull('first_kick_off')
            ->get()
            ->each(function (Gameweek $gameweek) {
                $firstFixture = $gameweek->fixtures()->orderBy('kick_off')->first();
                $gameweek->update(['first_kick_off' => $firstFixture->kick_off]);
            });

        return Command::SUCCESS;
    }
}
