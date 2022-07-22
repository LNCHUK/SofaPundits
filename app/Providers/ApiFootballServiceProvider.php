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
                apiKey: config('services.api_football.api_key'),
                timeout: config('services.api_football.timeout'),
                retryTimes: config('services.api_football.retry_times'),
                retryMilliseconds: config('services.api_football.retry_milliseconds'),
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
