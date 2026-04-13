<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Fix for older MySQL (key too long error)
        Schema::defaultStringLength(191);
    }
}
