<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use App\Models\ApiFootball\Fixture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Gameweek extends Model
{
    use GeneratesUuidOnCreation;
    use HasFactory;

    protected $fillable = [
        'group_id',
        'name',
        'start_date',
        'end_date',
        'description',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * @return BelongsToMany
     */
    public function fixtures(): BelongsToMany
    {
        return $this->belongsToMany(Fixture::class, 'gameweek_fixtures');
    }

    /**
     * @return Collection
     */
    public function getFixturesGroupedByDate(): Collection
    {
        return $this->fixtures()
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('kick_off', 'ASC')
            ->get()
            ->groupBy(function (Fixture $fixture) {
                return $fixture->kick_off->format('l jS F Y');
            });
    }
}
