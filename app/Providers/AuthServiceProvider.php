<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });
        Gate::define('isLeader', function ($user) {
            return $user->role === 'leader';
        });
        Gate::define('isUser', function ($user) {
            return $user->role === 'user';
        });

        Gate::define('isYourTicket', function ($user, $ticket) {
            return $ticket->society_id === $user->society_id;
        });
        Gate::before(function ($user, $ability) {
            if ($user->role =='admin') {
                return true;
            }
        });
        Passport::routes();
        //
    }
}
