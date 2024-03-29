<?php

namespace Database\Seeders;

use App\Enums\UserPreference;
use App\Models\Preference;
use Illuminate\Database\Seeder;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $preferences = [
            [
                'slug' => UserPreference::NOTIFICATIONS__GAMEWEEK_PUBLISHED_EMAIL,
                'type' => 'checkbox',
                'title' => 'Get notified by email when gameweeks are published',
                'category' => 'notifications',
                'description' => 'You will receive an email for each published gameweek in a group that you are a part of.',
                'default_value' => '1',
                'choices' => null,
            ],
            [
                'slug' => UserPreference::NOTIFICATIONS__GAMEWEEK_DEADLINE_REMINDER_EMAIL,
                'type' => 'checkbox',
                'title' => 'Get notified by email when a gameweek deadline is an hour away',
                'category' => 'notifications',
                'description' => 'This notification will remind you if you are part of a gameweek that is about to start, 
                    where you have not yet finished predicting all fixtures.',
                'default_value' => '1',
                'choices' => null,
            ],
            [
                'slug' => UserPreference::NEW_FEATURES__ENABLE_BETA_FEATURES,
                'type' => 'select',
                'title' => 'Test new features before release',
                'category' => 'new-features',
                'description' => 'Occasionally new features are released to a small set of users, before general release.
                    Enable this option if you\'d like to see these new features as soon as they are available.<br /><br/>
                    <strong>Warning:</strong> These features are often still being tested and may have some slight faults.
                    You may experience some bugs or quirks while we get the features completed!',
                'default_value' => '0',
                'choices' => [
                    1 => 'Yes',
                    0 => 'No',
                ],
            ],
        ];

        foreach ($preferences as $preference) {
            Preference::updateOrCreate([
                'slug' => $preference['slug']
            ], $preference);
        }

        // Clear the cache to ensure any changes are recognised
        cache()->flush();
    }
}
