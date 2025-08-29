<?php

namespace App\Providers;

use App\Listeners\SendWorkEmailVerificationListener;
use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Library;
use App\Models\User;
use App\Policies\EnsemblePolicy;
use App\Policies\LibraryPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
//Log::info('***** app.timezone: ' . config('app.timezone'));
//Log::info('***** app.locale: ' . config('app.locale'));
        Carbon::setLocale(config('app.timezone'));

        //authorize the founder for all gates
        Gate::before(function (User $user, string $ability) {

            if ($user->isFounder()) {
                return true;
            }

            return false;
        });

        //register policies
        Gate::policy(Ensemble::class, EnsemblePolicy::class);
        Gate::policy(Library::class, LibraryPolicy::class);
    }
}
