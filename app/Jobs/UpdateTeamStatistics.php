<?php

namespace App\Jobs;

use App\Models\ApiFootball\LeagueSeason;
use App\Models\ApiFootball\Team;
use App\Models\ApiFootball\TeamStatistics;
use App\Services\ApiFootball\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateTeamStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private string $teamId,
        private string $leagueSeasonId,
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apiFootball = resolve(Client::class);

        $team = Team::findOrFail($this->teamId);

        $leagueSeason = LeagueSeason::query()
            ->with(['league'])
            ->findOrFail($this->leagueSeasonId);

        $teamStats = TeamStatistics::query()
            ->where([
                'team_id' => $team->id,
                'league_season' => $leagueSeason->year,
                'league_id' => $leagueSeason->league->id,
            ])
            ->first();

        // Check if we've already updated this team today
        if ($teamStats && $teamStats->updated_at->startOfDay() === now()->startOfDay()) {
            Log::channel('api-logs')->info('Skipping team stats import. Already imported this team today.');
            return;
        }

        $response = $apiFootball->getTeamStatistics($leagueSeason, $team)->body();

        $responseBody = json_decode($response, true)['response'];

        $statsData = [
            'team_id' => $responseBody['team']['id'],
            'league_season' => $responseBody['league']['season'],
            'league_id' => $responseBody['league']['id'],
            'form' => $responseBody['form'],
            'fixtures' => $responseBody['fixtures'],
            'goals' => $responseBody['goals'],
            'biggest' => $responseBody['biggest'],
            'clean_sheet' => $responseBody['clean_sheet'],
            'failed_to_score' => $responseBody['failed_to_score'],
            'penalty' => $responseBody['penalty'],
            'lineups' => $responseBody['lineups'],
            'cards' => $responseBody['cards'],
        ];

        if ($teamStats) {
            $teamStats->update($statsData);
        } else {
            TeamStatistics::create($statsData);
        }
    }
}
