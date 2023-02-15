<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;

class UpdateBackedTeamScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backed-teams:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the backed team statistics';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all groups
        // TODO: Probably need a way of identifying groups that have finished and are no longer active
        $groups = Group::query()
            ->get();

        // Loop through all players who have a backed team
        $groups->each(function (Group $group) {
            $players = $group->users()
                ->whereHas('backedTeams', function ($query) use ($group) {
                    $query->where('group_id', $group->id);
                });

            // Get all fixtures from gameweeks that involve the backed team

        });

        // Update statistics accordingly

        return Command::SUCCESS;
    }
}
