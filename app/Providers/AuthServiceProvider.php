<?php

namespace App\Providers;

use App\Models\Gameweek;
use App\Models\Group;
use App\Policies\GameweeksPolicy;
use App\Policies\GroupsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Gameweek::class => GameweeksPolicy::class,
        Group::class => GroupsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if (config('temp.override_permissions') && $user->email === 'tom@lnch.co.uk') {
                return true;
            }
        });
    }
}
