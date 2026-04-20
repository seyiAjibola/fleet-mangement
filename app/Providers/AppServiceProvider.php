<?php

namespace App\Providers;

use App\Support\Compliance\ComplianceEntityMap;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        Relation::morphMap(ComplianceEntityMap::morphMap());

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
