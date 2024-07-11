<?php


use App\Http\Controllers\FounderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('founder', FounderController::class)
        ->name('founder');
});
