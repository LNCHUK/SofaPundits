<?php

namespace App\Jobs;

use App\Mail\GameweekDeadlineReminder;
use App\Models\Gameweek;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RemindUserOfGameweekDeadline implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    private $gameweek;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private int $userId,
        private string $gameweekUuid
    ) {
        $this->user = User::find($this->userId);
        $this->gameweek = Gameweek::query()->where('uuid', $this->gameweekUuid)->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Check if the user has completed all of their scores
        // If not, send the mail
        Mail::to($this->user)->queue(new GameweekDeadlineReminder($this->gameweek));
    }
}
