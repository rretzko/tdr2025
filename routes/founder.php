<?php


use App\Http\Controllers\FounderController;
use App\Http\Controllers\Founders\LogInAsController;
use App\Http\Controllers\Founders\PaypalManualEntryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', \App\Http\Middleware\FounderMiddleware::class])
    ->group(function () {
        Route::get('founder', FounderController::class)
            ->name('founder');
        Route::post('founder/logInAs', LogInAsController::class)
            ->name('founder.logInAs');
        Route::post('founder/paypalManualEntry', PaypalManualEntryController::class)
            ->name('founder.paypalManualEntry');
    });
