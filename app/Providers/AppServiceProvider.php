<?php

namespace App\Providers;

use App\Listeners\SendWorkEmailVerificationListener;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            SendWorkEmailVerificationListener::class,
        );

        Carbon::setLocale(config('app.timezone'));

        //authorize the founder for all gates
        Gate::before(function (User $user, string $ability) {
            if ($user->isFounder()) {
                return true;
            }
        });
    }
}
