<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('isAdmin', function(User $user) {
            return $user->isAdmin();
        });
        Gate::define('isUser', function(User $user) {
            return $user->isUser();
        });
        Gate::define('owner', function (User $user, $object) {
            return $user->id === $object->user_id;
        });
    }
}
