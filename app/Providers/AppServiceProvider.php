<?php

namespace App\Providers;

use App\Models\Avis;
use App\Observers\AvisObserver;
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
        // Register the Avis observer
        Avis::observe(AvisObserver::class);
    }
}
