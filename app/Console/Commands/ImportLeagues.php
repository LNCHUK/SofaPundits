<?php

namespace App\Console\Commands;

use App\Enums\Api;
use App\Models\ApiFootball\Country;
use App\Models\ApiFootball\League;
use App\Models\ApiFootball\LeagueSeason;
use App\Models\ApiLog;
use App\Models\CommandLog;
use App\Services\ApiFootball\Client;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Ramsey\Uuid\Lazy\LazyUuidFromString;

class ImportLeagues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:leagues {--trigger=manual}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the leagues data from the API Football service.';

    /**
     * @var CommandLog|null
     */
    private $commandLog;

    /**
     * @var LazyUuidFromString
     */
    private $commandUuid;

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
        $this->commandUuid = Str::uuid();
        $this->commandLog = CommandLog::create([
            'command' => 'import:leagues',
            'trigger' => $this->option('trigger'),
            'started_at' => now(),
        ]);

        Log::channel('api-logs')->info('import:leagues - Starting import', [
            'uuid' => (string) $this->commandUuid,
        ]);

        $leagues = $this->retrieveLeaguesFromApi();

        // If the leagues response is null we can fail the command here
        if (is_null($leagues)) {
            Log::channel('api-logs')->error('import:leagues - Import failed', [
                'uuid' => (string) $this->commandUuid,
            ]);

            $this->commandLog->markFailed();

            return Command::FAILURE;
        }

        $this->processLeagueResults($leagues);

        Log::channel('api-logs')->info('import:leagues - Import complete', [
            'uuid' => (string) $this->commandUuid,
        ]);

        $this->commandLog->markSuccessful();

        return Command::SUCCESS;
    }

    /**
     * @return Collection|null
     */
    private function retrieveLeaguesFromApi(): ?Collection
    {
        Log::channel('api-logs')->info('import:leagues - Retrieving league records from API', [
            'uuid' => (string) $this->commandUuid,
        ]);

        $apiLog = new ApiLog([
            'api_name' => Api::API_FOOTBALL,
            'endpoint' => '/leagues',
        ]);

        // Retrieve the API response
        try {
            $apiLog->started_at = now()->format('Y-m-d H:i:s');
            $leaguesResponse = $this->apiFootball->getLeagues();

            // Update the API log
            $apiLog->response_body = $leaguesResponse->body();
            $apiLog->was_successful = $leaguesResponse->successful();
        } catch (\Exception $exception) {
            Log::channel('api-logs')->error('import:leagues - Error importing leagues from API', [
                'uuid' => $this->commandUuid->__toString(),
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'stack' => $exception->getTraceAsString(),
            ]);

            $apiLog->was_successful = false;

            return null;
        }

        // Set the completed at time (successful or not)
        $apiLog->completed_at = now()->format('Y-m-d H:i:s');
        $apiLog->save();

        Log::channel('api-logs')->info('import:leagues - Finished retrieving leagues from API', [
            'uuid' => (string) $this->commandUuid,
        ]);

        return $leaguesResponse->collect('response');
    }

    private function processLeagueResults(Collection $leagues)
    {
        Log::channel('api-logs')
            ->info('import:leagues - ' . count($leagues) . ' leagues returned, processing results', [
                'uuid' => (string) $this->commandUuid,
            ]);

        foreach ($leagues as $leagueResult) {
            // TODO: Add a DTO here for the Country data
            $country = $this->manageLeagueCountry($leagueResult['country']);

            // Then, update or create the relevant League record
            $league = League::updateOrCreate([
                'id' => $leagueResult['league']['id']
            ], $leagueResult['league'] + [
                'country_id' => $country->id
            ]);

            // Finally, add all child seasons related to the League
            $this->processSeasonsForLeague($league, $leagueResult['seasons']);
        }
    }

    /**
     * @param array $country
     * @return Country
     */
    private function manageLeagueCountry(array $country): Country
    {
        // Firstly, retrieve or create the Country record required
        $dbCountry = Country::query()
            ->where('name', $country['name'])
            ->first();

        if (!$dbCountry) {
            Log::channel('api-logs')->info('import:leagues - New country data found, creating record', [
                'uuid' => (string) $this->commandUuid,
                'country' => $country,
            ]);

            $dbCountry = Country::create($country);
        }

        return $dbCountry;
    }

    private function processSeasonsForLeague(League $league, array $seasons): void
    {
        // Finally, add all child seasons related to the League
        foreach ($seasons as $season) {
            $season['is_current'] = $season['current'];
            LeagueSeason::updateOrCreate([
                'league_id' => $league->id,
                'year' => $season['year']
            ], $season);
        }
    }
}
