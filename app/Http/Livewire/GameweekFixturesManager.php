<?php

namespace App\Http\Livewire;

use App\Models\ApiFootball\Fixture;
use App\Models\Gameweek;
use Livewire\Component;

class GameweekFixturesManager extends Component
{
    public ?int $gameweekId = null;

    public ?string $search = '';

    public $possibleFixtures = [];

    public array $selectedFixtureIds = [];

    public function render()
    {
        $this->possibleFixtures = $this->getAvailableFixtures();

        return view('livewire.gameweek-fixtures-manager', [
            'gameweek' => $this->getGameweek()
        ]);
    }

    private function getGameweek()
    {
        return Gameweek::query()->find($this->gameweekId);
    }

    private function getAvailableFixtures()
    {
        $gameweek = $this->getGameweek();

        $query = Fixture::query()
            ->whereBetween('date', [
                $gameweek->start_date,
                $gameweek->end_date
            ]);

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

        return $query->limit(20)->get();
    }
}
