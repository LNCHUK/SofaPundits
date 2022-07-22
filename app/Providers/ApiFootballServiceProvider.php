<?php

namespace App\Providers;

use App\Services\ApiFootball\Client;
use Illuminate\Support\ServiceProvider;

class ApiFootballServiceProvider extends ServiceProvider
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
                baseUrl: config('services.api_football.base_url'),
                apiKey: config('services.api_football.api_key')
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
