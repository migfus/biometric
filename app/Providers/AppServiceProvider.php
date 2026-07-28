<?php

namespace App\Providers;

use App\Notifications\Channels\AblyChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Vite;
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
        Notification::extend('ably', fn ($app) => new AblyChannel);

        Vite::prefetch(concurrency: 3);
    }
}
