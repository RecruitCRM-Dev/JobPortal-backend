<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Log;
use App\Models\User;
use App\Models\Employer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserProfilePolicy',
        'App\Models\Employer' => 'App\Policies\EmployerProfilePolicy'
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

    }
}
