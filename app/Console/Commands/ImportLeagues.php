<?php

namespace App\Console\Commands;

use App\Models\ApiFootball\Country;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\LeagueSeason;
use App\Services\ApiFootball\Client;
use Illuminate\Console\Command;

class ImportLeagues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:leagues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the leagues data from the API Football service.';

    public function __construct(
        private Client $apiFootball
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Retrieve the API response
        $leaguesResponse = $this->apiFootball->getLeagues();

        // Collate into a collection of results
        $leagueResults = collect();
        foreach ($leaguesResponse->collect('response') as $item) {
            $leagueResults->add(
                $item,
            );
        }

        foreach ($leagueResults as $leagueResult) {
            // Firstly, retrieve or create the Country record required
            $country = Country::query()
                ->where('name', $leagueResult['country']['name'])
                ->first();

            if (!$country) {
                $country = Country::create($leagueResult['country']);
            }

            // Then, update or create the relevant League record
            $league = League::updateOrCreate([
                'id' => $leagueResult['league']['id']
            ], $leagueResult['league'] + [
                'country_id' => $country->id
            ]);

            // Finally, add all child seasons related to the League
            foreach ($leagueResult['seasons'] as $season) {
                $season['is_current'] = $season['current'];
                LeagueSeason::updateOrCreate([
                    'league_id' => $league->id,
                    'year' => $season['year']
                ], $season);
            }
        }

        return 0;
    }
}
