<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Calls all core seeders. Core seeders should be able to run many times without
        // causing duplicated data
        $this->call(PreferenceSeeder::class);
    }
}
