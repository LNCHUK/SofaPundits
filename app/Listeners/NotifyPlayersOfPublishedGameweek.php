<?php

namespace App\Listeners;

use App\Enums\UserPreference;
use App\Events\GameweekPublishedEvent;
use App\Jobs\RemindUserOfGameweekDeadline;
use App\Mail\GameweekWasPublished;
use App\Models\Gameweek;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyPlayersOfPublishedGameweek
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Handle the event.
     *
     * @param  \App\Events\GameweekPublishedEvent  $event
     * @return void
     */
    public function handle(GameweekPublishedEvent $event)
    {
        // Get all players
        $players = $event->gameweek->group->users;

        // Get the first fixture so we can schedule the reminder
        /** @var Carbon $firstFixture */
        $firstFixture = $event->gameweek
            ->fixtures()
            ->orderBy('kick_off')
            ->first();

        // Send email to everyone
        foreach ($players as $player) {
            // Send the email IF the user has the relevant preference enabled
            if (UserPreference::NOTIFICATIONS__GAMEWEEK_PUBLISHED_EMAIL()->getValueForUser($player)) {
                Mail::to($player)->queue(new GameweekWasPublished($event->gameweek));
            }

            RemindUserOfGameweekDeadline::dispatch(
                userId: $player->id,
                gameweekUuid: $event->gameweek->uuid
            )->delay($firstFixture->kick_off->subHour());
        }
    }
}
