<?php

namespace App\Http\Livewire;

use App\Models\ApiFootball\Fixture;
use App\Models\ApiFootball\League;
use App\Models\Gameweek;
use Livewire\Component;

class GameweekFixturesManager extends Component
{
    public ?int $gameweekId = null;

    public ?int $leagueId = null;

    public ?string $search = '';

    public $possibleFixtures = [];

    public array $selectedFixtureIds = [];

    public function mount()
    {
        $gameweek = $this->getGameweek();

        $this->selectedFixtureIds = $gameweek->fixtures()->pluck('fixtures.id')->toArray();
    }

    public function render()
    {
        $this->possibleFixtures = $this->getAvailableFixtures();

        return view('livewire.gameweek-fixtures-manager', [
            'gameweek' => $this->getGameweek(),
            'chosenFixtures' => $this->getChosenFixtures(),
            'leagues' => $this->getLeagues(),
        ]);
    }

    private function getGameweek()
    {
        return Gameweek::query()->find($this->gameweekId);
    }

    private function getChosenFixtures()
    {
        return Fixture::query()
            ->whereIn('id', $this->selectedFixtureIds)
            ->orderBy('kick_off', 'ASC')
            ->get();
    }

    private function getLeagues()
    {
        return League::getLeaguesInUseAsSelectArray();
    }

    private function getAvailableFixtures()
    {
        $gameweek = $this->getGameweek();

        $query = Fixture::query()
            ->whereNotIn('id', $this->selectedFixtureIds)
            ->whereBetween('date', [
                $gameweek->start_date,
                $gameweek->end_date
            ]);

        if ($this->leagueId) {
            // Filter by League
            $query->whereHas('leagueSeason', function ($query) {
                $query->whereHas('league', function ($query) {
                    $query->where('id', $this->leagueId);
                });
            });
        }

        if ($this->search) {
            // Search for home team
            $query->where(function ($query) {
                $query->whereHas('homeTeam', function ($query) {
                    $query->where('name', 'LIKE', "%".$this->search."%");
                })->orWhereHas('awayTeam', function ($query) {
                    $query->where('name', 'LIKE', "%".$this->search."%");
                });
            });
        }

        return $query
            ->orderBy('kick_off', 'ASC')
            ->limit(20)
            ->get();
    }

    public function selectFixture(int $fixtureId)
    {
        $this->selectedFixtureIds = [
            ...$this->selectedFixtureIds,
            $fixtureId
        ];
    }

    public function removeSelectedFixture(int $fixtureId)
    {
        $chosenIds = $this->selectedFixtureIds;
        $idToRemove = null;

        array_walk($chosenIds, function ($value, $key) use ($fixtureId, &$idToRemove) {
            if  ($value == $fixtureId) {
                $idToRemove = $key;
            }
        });

        if ($idToRemove !== null) {
            unset($chosenIds[$idToRemove]);
        }

        $this->selectedFixtureIds = $chosenIds;
    }
}
