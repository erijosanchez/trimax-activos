<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // Define Gates for permissions
        Gate::define('canManageInventory', function (User $user) {
            return $user->canManageInventory();
        });

        Gate::define('canPerformMaintenance', function (User $user) {
            return $user->canPerformMaintenance();
        });

        Gate::define('isAdmin', function (User $user) {
            return $user->isAdmin();
        });
    }
}

