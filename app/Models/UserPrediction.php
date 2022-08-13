<?php

namespace App\Models;

use App\Enums\FixtureStatusCode;
use App\Enums\UserPredictionResult;
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

    /**
     * Returns the status of the prediction, based on the score and result
     * of the parent fixture.
     *
     * @return UserPredictionResult
     */
    public function getResultAttribute(): UserPredictionResult
    {
        $fixture = $this->fixture;

        // TODO: This will need updating to use any valid status...
        if ($fixture->status_code == FixtureStatusCode::MATCH_FINISHED) {
            if ($this->isCorrectScore()) {
                return UserPredictionResult::CORRECT_SCORE();
            }

            if ($this->isCorrectResult()) {
                return UserPredictionResult::CORRECT_RESULT();
            }

            return UserPredictionResult::INCORRECT_RESULT();
        }

        return UserPredictionResult::PENDING();
    }

    /**
     * Returns true if the home and away goals matches that of the parent
     * Fixture record.
     *
     * @return bool
     */
    public function isCorrectScore(): bool
    {
        return $this->home_score == $this->fixture->goals['home']
            && $this->away_score == $this->fixture->goals['away'];
    }

    /**
     * Returns true if the result is correct. Results can be a home win, an
     * away win or a draw, and the parent Fixture must have the same result.
     *
     * @return bool
     */
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

    public function getPointsAttribute(): ?int
    {
        return optional($this->result)->points();
    }
}
