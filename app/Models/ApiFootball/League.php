<?php

namespace App\Models\ApiFootball;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'country_id',
        'name',
        'type',
        'logo',
    ];

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Returns a Collection of all Leagues in active use, as defined
     * by the config file.
     *
     * @return Collection
     */
    public static function getLeaguesInUse(): Collection
    {
        return self::query()
            ->where(function ($query) {
                $selectedLeagues = config('leagues.chosen-leagues');

                foreach ($selectedLeagues as $selectedLeague) {
                    $query = $query->orWhere(function ($query) use ($selectedLeague) {
                        $query->where('name', $selectedLeague['name'])
                            ->whereHas('country', function ($query) use ($selectedLeague) {
                                $query->where('name', $selectedLeague['country']);
                            });
                    });
                }
            })
            ->get();
    }

    /**
     * Returns an array of all Leagues in active use, keyed by ID and using the name
     * of the League as the label.
     *
     * @return array
     */
    public static function getLeaguesInUseAsSelectArray(): array
    {
        $leagues = self::getLeaguesInUse()->mapWithKeys(function (League $league) {
            return [$league->id => $league->name];
        })->toArray();

        return ['' => 'All Leagues'] + $leagues;
    }
}
