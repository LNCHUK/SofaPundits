<?php

namespace App\Providers;

use App\Concerns\Services\SofaPunditsScout;
use App\Services\Scout\ApiClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the API Client implementation of Scout to the interface
        $this->app->bind(SofaPunditsScout::class, ApiClient::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('Europe/London');
    }
}
