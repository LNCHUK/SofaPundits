<?php

namespace App\Providers;

use App\Services\Scout\Client;
use Illuminate\Support\ServiceProvider;

class SofaPunditsScoutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            return new Client(
                baseUrl: config('services.sp_scout.base_url'),
                apiKey: config('services.sp_scout.api_key')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
