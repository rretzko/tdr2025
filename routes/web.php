<?php

use App\Http\Controllers\Events\EventManageEditController;
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

    //Candidates
    Route::get('candidates', \App\Http\Controllers\Events\Versions\Participations\CandidateController::class)
        ->name('candidates');

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

    //EVENT
    Route::get('event/new', \App\Http\Controllers\Events\EventManageCreateController::class)
        ->name('event.create');
    Route::get('event/edit/{event}', EventManageEditController::class)
        ->middleware('can:update,event') //check EventPolicy
        ->name('event.edit');

    //EVENTS
    Route::get('events/dashboard', \App\Http\Controllers\Events\EventsDashboardController::class)
        ->name('events.dashboard');
    Route::get('events/participation/table', \App\Http\Controllers\Events\EventsParticipationController::class)
        ->name('events.participation.table');
    Route::get('events/adjudication', \App\Http\Controllers\Events\EventsAdjudicationController::class)
        ->name('events.adjudication');
    Route::get('events/manage', \App\Http\Controllers\Events\EventsManageController::class)
        ->name('events.manage');

    //INVENTORIES
    Route::get('ensembles/inventory', [\App\Http\Controllers\Ensembles\Inventories\InventoryController::class, 'index'])
        ->name('inventories');
    Route::get('ensembles/inventory/new',
        [\App\Http\Controllers\Ensembles\Inventories\InventoryController::class, 'create'])
        ->name('inventory.create');
    Route::get('ensembles/inventory/{inventory}',
        [\App\Http\Controllers\Ensembles\Inventories\InventoryController::class, 'edit'])
        ->name('inventory.edit');

    //LIBRARIES
    Route::get('libraries', [\App\Http\Controllers\Libraries\libraryController::class, 'index'])
        ->name('libraries');

    //PARTICIPATIONS (Event)
    Route::get('participation/dashboard/{version}',
        \App\Http\Controllers\Events\Versions\Participations\ParticipationActiveController::class)
        ->name('participation.dashboard');
    Route::get('participation/results/{version}',
        \App\Http\Controllers\Events\Versions\Participations\ParticipationResultsController::class)
        ->name('participation.results');

    //PDFs
    Route::get('pdf/application/{candidate}', \App\Http\Controllers\Pdfs\ApplicationPdfController::class)
        ->name('pdf.application');

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
    Route::get('student/edit/{school_student}', [\App\Http\Controllers\Students\StudentController::class, 'edit'])
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

    //VERSIONS
    Route::get('version/configs', [\App\Http\Controllers\Events\Versions\VersionConfigController::class, 'edit'])
        ->name('version.configs');

    //VERSIONS.DATES
    Route::get('version/dates', [\App\Http\Controllers\Events\Versions\VersionDateController::class, 'edit'])
        ->name('version.dates');

    //VERSIONS.PARTICIPANTS
    Route::get('version/participants',
        [\App\Http\Controllers\Events\Versions\VersionParticipantController::class, 'index'])
        ->name('version.participants');
    Route::get('version/participant/edit/{participant}',
        [\App\Http\Controllers\Events\Versions\VersionParticipantController::class, 'index'])
        ->name('version.participant.edit');

    //VERSIONS.PITCHFILES
    Route::get('version/pitchFiles', \App\Http\Controllers\Events\Versions\VersionPitchFileController::class)
        ->name('version.pitchFiles');

    //VERSIONS.ROLES
    Route::get('version/roles', \App\Http\Controllers\Events\Versions\VersionRoleController::class)
        ->name('version.roles');

    Route::get('version/profile', [\App\Http\Controllers\Events\Versions\VersionProfileController::class, 'edit'])
        ->name('version.edit');
    Route::get('versions/all/{event}', [\App\Http\Controllers\Events\Versions\VersionsController::class, 'index'])
        ->name('versions.index');
    Route::get('versions/current/{event}', \App\Http\Controllers\Events\Versions\CurrentVersionController::class)
        ->name('version.current');
    Route::get('version/new/{event}', [\App\Http\Controllers\Events\Versions\VersionProfileController::class, 'create'])
        ->name('version.create');

    Route::get('version/dashboard/{version}', [\App\Http\Controllers\Events\Versions\VersionController::class, 'show'])
        ->name('version.show');

    //VERSIONS.SCORING
    Route::get('version/scoring', \App\Http\Controllers\Events\Versions\VersionScoringController::class)
        ->name('version.scoring');
    //for filament
    Route::resource('events.versions.version-scorings',
        \App\Http\Controllers\Events\Versions\VersionScoringController::class);


//    Route::get('event/edit/{event}', EventManageEditController::class)
//        ->middleware('can:update,event') //check EventPolicy
//        ->name('event.edit');
});

require __DIR__.'/auth.php';
require __DIR__.'/founder.php';
