<?php

namespace App\Providers;

use App\Models\Events\Versions\Scoring\Score;
use App\Observers\ScoreObserver;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
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
        Score::observe(ScoreObserver::class);
    }
}
