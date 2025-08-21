<?php

use App\Http\Controllers\FounderController;
use Illuminate\Support\Facades\Route;

Route::get('/tutorials', function () {
    return view('tutorials.dashboard');
})
    ->name('tutorials.dashboard');

Route::get('/tutorial/tdroverview', fn() => view('tutorials.tdroverview'))
    ->name('tutorial.tdroverview');
Route::get('/tutorial/schools', fn() => view('tutorials.schools'))
    ->name('tutorial.schools');
Route::get('/tutorial/students', fn() => view('tutorials.students'))
    ->name('tutorial.students');
Route::get('/tutorial/ensembles', fn() => view('tutorials.ensembles'))
    ->name('tutorial.ensembles');
Route::get('/tutorial/libraries', fn() => view('tutorials.libraries'))
    ->name('tutorial.libraries');
Route::get('/tutorial/programs', fn() => view('tutorials.programs'))
    ->name('tutorial.programs');
Route::get('/tutorial/events', fn() => view('tutorials.events'))
    ->name('tutorial.events');
Route::get('tutorial/events/manageEventsDetail', fn() => view('tutorials.events.manageEventsDetail'))
    ->name('tutorial.events.manageEventsDetail');
Route::get('tutorial/events/eventParticipationDetail', fn() => view('tutorials.events.eventParticipationDetail'))
    ->name('tutorial.events.eventParticipationDetail');
Route::get('/tutorial/profile', fn() => view('tutorials.profile'))
    ->name('tutorial.profile');
