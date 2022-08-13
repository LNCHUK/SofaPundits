<?php

namespace App\Models;

use App\Enums\FixtureStatusCode;
use App\Models\ApiFootball\Fixture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gameweek_id',
        'fixture_id',
        'home_score',
        'away_score',
        'points',
    ];

    protected $casts = [
        'home_score' => 'int',
        'away_score' => 'int',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function gameweek(): BelongsTo
    {
        return $this->belongsTo(Gameweek::class);
    }

    /**
     * @return BelongsTo
     */
    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }

    public function getResultAttribute(): string
    {
        $fixture = $this->fixture;

        // TODO: This will need updating to use any valid status...
        if ($fixture->status_code == FixtureStatusCode::MATCH_FINISHED) {
            // If the score is exact, correct score
            // TODO: Replace with an enum...
            if ($this->isCorrectScore()) {
                return 'Correct Score';
            }

            if ($this->isCorrectResult()) {
                return 'Correct Result';
            }

            return 'Incorrect Result';
        } else {
            // Match is not finished
            return 'Pending';
        }
    }

    public function isCorrectScore(): bool
    {
        return $this->home_score == $this->fixture->goals['home']
            && $this->away_score == $this->fixture->goals['away'];
    }

    public function isCorrectResult(): bool
    {
        $fixture = $this->fixture;

        $correctHomeWin = $this->home_score > $this->away_score
            && (int) $fixture->goals['home'] > (int) $fixture->goals['away'];

        $correctAwayWin = $this->away_score > $this->home_score
            && (int) $fixture->goals['away'] > (int) $fixture->goals['home'];

        $correctDraw = $this->home_score == $this->away_score
            && (int) $fixture->goals['home'] == (int) $fixture->goals['away'];

        return $correctHomeWin || $correctAwayWin || $correctDraw;
    }
}
