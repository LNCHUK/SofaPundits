<?php

namespace Database\Seeders;

use App\Models\Preference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'slug' => 'notifications-gameweek_published-email',
                'type' => 'checkbox',
                'title' => 'Get notified by email when gameweeks are published',
                'category' => 'notifications',
                'description' => 'You will receive an email for each published gameweek in a group that you are a part of.',
                'default_value' => '1',
                'choices' => null,
            ],
            [
                'slug' => 'notifications-gameweek_deadline_reminder-email',
                'type' => 'checkbox',
                'title' => 'Get notified by email when a gameweek deadline is an hour away',
                'category' => 'notifications',
                'description' => 'This notification will remind you if you are part of a gameweek that is about to start, 
                    where you have not yet finished predicting all fixtures.',
                'default_value' => '1',
                'choices' => null,
            ],
        ];

        foreach ($preferences as $preference) {
            Preference::updateOrCreate([
                'slug' => $preference['slug']
            ], $preference);
        }
    }
}
