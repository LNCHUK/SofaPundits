<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static INCORRECT_RESULT()
 * @method static static CORRECT_RESULT()
 * @method static static CORRECT_SCORE()
 */
final class UserPredictionResult extends Enum
{
    const PENDING = 'pending';
    const INCORRECT_RESULT = 'incorrect_result';
    const CORRECT_RESULT = 'correct_result';
    const CORRECT_SCORE = 'correct_score';

    /**
     * Returns the points that were earned based on this result.
     *
     * @return int|null
     */
    public function points(): ?int
    {
        switch ($this) {
            case self::INCORRECT_RESULT:
                return 0;
            case self::CORRECT_RESULT:
                return 1;
            case self::CORRECT_SCORE:
                return 3;
            case self::PENDING:
            default:
                return null;
        }
    }

    /**
     * Returns the points that were earned based on this result.
     *
     * @return int|null
     */
    public function cssClass(): ?string
    {
        switch ($this) {
            case self::INCORRECT_RESULT:
                return 'incorrect-result-gradient';
            case self::CORRECT_RESULT:
                return 'correct-result-gradient';
            case self::CORRECT_SCORE:
                return 'correct-score-gradient';
            case self::PENDING:
            default:
                return null;
        }
    }
}
