<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    //HOME
    Route::get('home', \App\Http\Controllers\HomeController::class)
        ->name('home');

    //PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //SCHOOLS
    Route::get('schools', \App\Http\Controllers\Schools\SchoolsController::class)
        ->name('schools');
    Route::get('school/new', [\App\Http\Controllers\Schools\SchoolController::class, 'create'])
        ->name('school.create');
});

require __DIR__.'/auth.php';
