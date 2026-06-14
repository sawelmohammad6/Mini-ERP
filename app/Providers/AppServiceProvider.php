<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use App\Policies\ReportPolicy;
use App\Policies\SettingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Register authorization gates for admin-only modules.
        // Why Gates + Policies instead of middleware role checks?
        // -------------------------------------------------------
        // Gates provide a single, testable layer that works with @can
        // directives in Blade, can: middleware on routes, and
        // authorize() calls in controllers.
        Gate::policy(User::class, UserPolicy::class);
        Gate::define('view-reports', [ReportPolicy::class, 'viewAny']);
        Gate::define('view-settings', [SettingPolicy::class, 'view']);
        Gate::define('update-settings', [SettingPolicy::class, 'update']);

        if (Schema::hasTable('settings')) {
            View::composer('*', function ($view) {
                $view->with('setting', Setting::first() ?? new Setting());
            });
        } else {
            View::composer('*', function ($view) {
                $view->with('setting', new Setting());
            });
        }
    }
}
