<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
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
