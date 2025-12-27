<?php

namespace App\Providers;

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
        \Illuminate\Database\Eloquent\Model::shouldBeStrict(! $this->app->isProduction());
        
        // Ensure we don't accidentally leak stack traces in prod even if env is messed up
        if ($this->app->isProduction()) {
            config(['app.debug' => false]);
        }
    }
}
