<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Ensembles\Assets\AssetsTableComponent;
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

    //ENSEMBLES
    Route::get('ensembles', [\App\Http\Controllers\Ensembles\EnsembleController::class, 'index'])
        ->name('ensembles');
    Route::get('ensemble/new', [\App\Http\Controllers\Ensembles\EnsembleController::class, 'create'])
        ->name('ensemble.create');
    Route::get('ensemble/edit/{ensemble}', [\App\Http\Controllers\Ensembles\EnsembleController::class, 'edit'])
        ->name('ensemble.edit');
    Route::get('ensemble/remove/{ensemble}', [\App\Http\Controllers\Ensembles\EnsembleController::class, 'delete'])
        ->name('ensemble.delete');

    //ENSEMBLES:ASSETS
    Route::get('ensembles/assets', [\App\Http\Controllers\Ensembles\Assets\AssetController::class, 'index'])
        ->name('assets');
//    Route::get('ensembles/asset/edit/{asset}', [\App\Http\Controllers\Ensembles\Assets\AssetController::class, 'edit'])
//        ->name('asset.edit');

    //ENSEMBLES:MEMBERS
    Route::get('ensembles/members', [\App\Http\Controllers\Ensembles\Members\MemberController::class, 'index'])
        ->name('members');
    Route::get('ensembles/members/edit/{member}',
        [\App\Http\Controllers\Ensembles\Members\MemberController::class, 'edit'])
        ->name('schoolEnsembleMember.edit');
    Route::get('ensembles/members/new', [\App\Http\Controllers\Ensembles\Members\MemberController::class, 'create'])
        ->name('schoolEnsembleMember.create');

    //EVENTS
    Route::get('events', [\App\Http\Controllers\Events\EventController::class, 'index'])
        ->name('events');

    Route::get('libraries', [\App\Http\Controllers\Libraries\libraryController::class, 'index'])
        ->name('libraries');

    //SCHOOLS
    Route::get('schools', \App\Http\Controllers\Schools\SchoolsController::class)
        ->name('schools');
    Route::get('school/new', [\App\Http\Controllers\Schools\SchoolController::class, 'create'])
        ->name('school.create');
    Route::get('school/edit/{school}', [\App\Http\Controllers\Schools\SchoolController::class, 'edit'])
        ->name('school.edit');

    //STUDENTS
    Route::get('students', \App\Http\Controllers\Students\StudentsController::class)
        ->name('students');
    Route::get('student/new', [\App\Http\Controllers\Students\StudentController::class, 'create'])
        ->name('student.create');
    Route::get('student/edit/{student}', [\App\Http\Controllers\Students\StudentController::class, 'edit'])
        ->name('student.edit');
    Route::get('student/comms/edit/{student}',
        [\App\Http\Controllers\Students\StudentCommunicationsController::class, 'edit'])
        ->name('student.comms.edit');
    Route::get('student/ec/edit/{student}',
        [\App\Http\Controllers\Students\StudentEmergencyContactController::class, 'edit'])
        ->name('student.ec.edit');
    Route::get('student/reset/{student}',
        \App\Http\Controllers\Students\StudentResetPasswordController::class) //invokable
    ->name('student.reset');
});

require __DIR__.'/auth.php';
