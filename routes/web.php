<?php

use App\Http\Controllers\Events\EventManageEditController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//ePayment
Route::post('ePaymentReceipt', [\App\Http\Controllers\ePayments\EpaymentReceiptController::class, 'store'])
    ->name('ePaymentReceipt');

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

    //JUDGE ASSIGNMENTS
    Route::get('version/judgeAssignment',
        \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('judgeAssignment');

    //ADJUDICATION
    Route::get('adjudication', \App\Http\Controllers\Events\Versions\Adjudications\AdjudicationController::class)
        ->name('adjudication');

    //LIBRARIES
    Route::get('libraries', [\App\Http\Controllers\Libraries\libraryController::class, 'index'])
        ->name('libraries');

    //OBLIGATIONS
    Route::get('obligations', \App\Http\Controllers\Events\Versions\Participations\ObligationController::class)
        ->name('obligations');

    //PARTICIPATIONS (Event)
    Route::get('participation/dashboard/{version}',
        \App\Http\Controllers\Events\Versions\Participations\ParticipationActiveController::class)
        ->name('participation.dashboard');
    Route::get('participation/results/{version}',
        \App\Http\Controllers\Events\Versions\Participations\ParticipationResultsController::class)
        ->name('participation.results');

    //PARTICIPATION.ESTIMATE
    Route::get('estimate', \App\Http\Controllers\Events\Versions\Participations\EstimateController::class)
        ->name('estimate');

    //PDFs
    Route::get('pdf/application/{candidate}', \App\Http\Controllers\Pdfs\ApplicationPdfController::class)
        ->name('pdf.application');
    Route::get('pdf/contract/{candidate}', \App\Http\Controllers\Pdfs\ContractPdfController::class)
        ->name('pdf.contract');
    Route::get('pdf/estimate/{version}', \App\Http\Controllers\Pdfs\EstimatePdfController::class)
        ->name('pdf.estimate');
    Route::get('pdf/candidateScore/{candidate}', \App\Http\Controllers\Pdfs\CandidateScorePdfController::class)
        ->name('pdf.candidateScore');
    Route::get('pdf/candidateScoresSchool/', \App\Http\Controllers\Pdfs\CandidateScoresSchoolPdfController::class)
        ->name('pdf.candidateScoresSchool');
    Route::get('pdf/candidateScoresConfidential/',
        \App\Http\Controllers\Pdfs\CandidateScoresConfidentialPdfController::class)
        ->name('pdf.candidateScoresConfidential');
    Route::get('pdf/adjudicationBackupPaper/{roomId}',
        \App\Http\Controllers\Pdfs\AdjudicationBackupPaperController::class)
        ->name('pdf.adjudicationBackupPaper');
    Route::get('pdf/adjudicationMonitorChecklist/{roomId}',
        \App\Http\Controllers\Pdfs\AdjudicationMonitorChecklistController::class)
        ->name('pdf.adjudicationMonitorChecklist');
    Route::get('pdf/registrationCards/candidates/{candidate}',
        [\App\Http\Controllers\Pdfs\RegistrationCardsController::class, 'candidate'])
        ->name('pdf.registrationCards.candidates');
    Route::get('pdf/registrationCards/schools/{school}',
        [\App\Http\Controllers\Pdfs\RegistrationCardsController::class, 'school'])
        ->name('pdf.registrationCards.schools');
    Route::get('pdf/registrationCards/voiceParts/{voicePart}',
        [\App\Http\Controllers\Pdfs\RegistrationCardsController::class, 'voicePart'])
        ->name('pdf.registrationCards.voiceParts');

    //PARTICIPATION.PITCHFILES
    Route::get('pitchFiles', \App\Http\Controllers\Events\Versions\Participations\PitchFileController::class)
        ->name('pitchFiles');

    //SCHOOL TIMESLOTS
    Route::get('version/timeslots',
        \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('timeslotAssignment');

    //SCHOOLS
    Route::get('schools', \App\Http\Controllers\Schools\SchoolsController::class)
        ->name('schools');
    Route::get('school/new', [\App\Http\Controllers\Schools\SchoolController::class, 'create'])
        ->name('school.create');
    Route::get('school/edit/{school}', [\App\Http\Controllers\Schools\SchoolController::class, 'edit'])
        ->name('school.edit');

    //STUDENT DOSSIER
    Route::get('studentDossier', \App\Http\Controllers\Students\StudentDossierController::class)
        ->name('studentDossier');

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

    //VERSIONS.REPORTS
    Route::get('version/reports', \App\Http\Controllers\Events\Versions\VersionReportController::class)
        ->name('version.reports');
    Route::get('version/reports/backupPaper', \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('version.reports.backupPaper');
    Route::get('version/reports/backupCsv', \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('version.reports.backupCsv');
    Route::get('version/reports/monitorChecklist', \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('version.reports.monitorChecklist');
    Route::get('version/reports/obligatedTeachers',
        \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('version.reports.obligatedTeachers');
    Route::get('version/reports/participatingTeachers',
        \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('version.reports.participatingTeachers');
    Route::get('version/reports/participatingSchools',
        \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('version.reports.participatingSchools');
    Route::get('version/reports/participatingStudents',
        \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('version.reports.participatingStudents');
    Route::get('version/reports/registrationCards',
        \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('version.reports.registrationCards');
    Route::get('version/reports/studentCounts',
        \App\Http\Controllers\Tdr\LivewireController::class)
        ->name('version.reports.studentCounts');

    //VERSIONS.ROLES
    Route::get('version/roles', \App\Http\Controllers\Events\Versions\VersionRoleController::class)
        ->name('version.roles');

    //VERSIONS.TABROOM
    //VERSIONS.TABROOM.CLOSEVERSION
    Route::get('version/tabroom/close', \App\Http\Controllers\Events\Versions\Tabrooms\CloseVersionController::class)
        ->name('version.tabroom.close');

    //VERSIONS.TABROOM.CUTOFF
    Route::get('version/tabroom/cutoff',
        \App\Http\Controllers\Events\Versions\Tabrooms\TabroomCutoffController::class)
        ->name('version.tabroom.cutoff');

    //VERSIONS.TABROOM.REPORTS
    Route::get('version/tabroom/reports',
        \App\Http\Controllers\Events\Versions\Tabrooms\TabroomReportController::class)
        ->name('version.tabroom.reports');
    //VERSIONS.TABROOM.REPORTS.SCORES-BY-VOICE-PART
    Route::get('versions/tabroom/reports/byVoicePart/{voicePart}/{private}/{eventEnsembleId}',
        \App\Http\Controllers\Pdfs\ScoringRosterController::class)
        ->name('versions.tabroom.reports.byVoicePart');
    //VERSIONS.TABROOM.REPORTS.ALLPRIVATE
    Route::get('versions/tabroom/reports/allPrivate/{voicePart}/{private}/{eventEnsembleId}',
        \App\Http\Controllers\Pdfs\ScoringRosterController::class)
        ->name('versions.tabroom.reports.allPrivate');

    //VERSIONS.TABROOM.SCORING
    Route::get('version/tabroom/scoring',
        \App\Http\Controllers\Events\Versions\Tabrooms\TabroomScoringController::class)
        ->name('version.tabroom.scoring');

    //VERSIONS.TABROOM.TRACKING
    Route::get('version/tabroom/tracking',
        \App\Http\Controllers\Events\Versions\Tabrooms\TabroomTrackingController::class)
        ->name('version.tabroom.tracking');

    //VERSIONS.STUDENT TRANSFER
    Route::get('version/studentTransfer', \App\Http\Controllers\Events\Versions\StudentTransferController::class)
        ->name('studentTransfer');


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

    //VERIFIED SIGNED ROUTES
    Route::get('/invite/{version}/{user}', \App\Http\Controllers\Events\Versions\InviteVersionUserController::class)
        ->name('inviteVersionUser');

//    Route::get('event/edit/{event}', EventManageEditController::class)
//        ->middleware('can:update,event') //check EventPolicy
//        ->name('event.edit');
});

require __DIR__.'/auth.php';
require __DIR__.'/founder.php';
