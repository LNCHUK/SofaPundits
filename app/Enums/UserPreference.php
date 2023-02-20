<?php declare(strict_types=1);

namespace App\Enums;

use App\Models\User;
use BenSampo\Enum\Enum;

/**
 * @method static static NOTIFICATIONS__GAMEWEEK_PUBLISHED_EMAIL()
 * @method static static NOTIFICATIONS__GAMEWEEK_DEADLINE_REMINDER_EMAIL()
 * @method static static NEW_FEATURES__ENABLE_BETA_FEATURES()
 */
final class UserPreference extends Enum
{
    const NOTIFICATIONS__GAMEWEEK_PUBLISHED_EMAIL = 'notifications-gameweek_published-email';
    const NOTIFICATIONS__GAMEWEEK_DEADLINE_REMINDER_EMAIL = 'notifications-gameweek_deadline_reminder-email';
    const NEW_FEATURES__ENABLE_BETA_FEATURES = 'new_features-enable_beta_features';

    /**
     * @return string
     */
    public function getValueForAuthenticatedUser(): string
    {
        return $this->getValueForUser(auth()->user());
    }

    /**
     * @param User $user
     * @return string
     */
    public function getValueForUser(User $user): string
    {
        $preference = $user
            ->getPreferencesUngrouped()
            ->firstWhere('slug', $this->value);

        return $preference->user_selected_value ?? $preference->default_value;
    }
}
