<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        Paginator::useBootstrapFive();

        Carbon::setLocale(app()->getLocale());

        View::composer('layouts.app', function ($view): void {
            $view->with(
                'unreadNotificationsCount',
                Auth::check() ? Auth::user()->unreadNotifications()->count() : 0
            );
        });
    }
}
