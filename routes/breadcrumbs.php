<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

//Candidates
Breadcrumbs::for('candidates', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('participation dashboard', $id);
    $trail->push('Candidates', route('candidates', $id));
});
Breadcrumbs::for('candidates table', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('participation dashboard', $id);
    $trail->push('Candidates', route('candidates', $id));
});
Breadcrumbs::for('participation results', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('participation dashboard', $id);
    $trail->push('Results', route('participation.results', $id));
});

//Teacher Pitch Files
Breadcrumbs::for('teacher pitch files', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('participation dashboard', $id);
    $trail->push('Pitch Files', route('pitchFiles', $id));
});

//Participation Dashboard
Breadcrumbs::for('participation dashboard', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('events participation', $id);
    $trail->push('Participation Dashboard', route('participation.dashboard', $id));
});

// Ensembles
Breadcrumbs::for('ensembles', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Ensembles', route('ensembles'));
});

// Ensemble.create
Breadcrumbs::for('ensemble create', function (BreadcrumbTrail $trail) {
    $trail->parent('ensembles');
    $trail->push('Add Ensemble', route('ensemble.create'));
});

// Ensemble.edit
Breadcrumbs::for('ensemble edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('ensembles');
    $trail->push('Edit Ensemble', route('ensemble.edit', ['ensemble' => $id]));
});

// Ensembles.Assets
Breadcrumbs::for('assets', function (BreadcrumbTrail $trail) {
    $trail->parent('ensembles');
    $trail->push('Assets', route('assets'));
});

// Ensembles.Inventories
Breadcrumbs::for('inventories', function (BreadcrumbTrail $trail) {
    $trail->parent('ensembles');
    $trail->push('Inventories', route('inventories'));
});

// Ensembles.Inventory.Create
Breadcrumbs::for('inventory create', function (BreadcrumbTrail $trail) {
    $trail->parent('inventories');
    $trail->push('Add Inventory', route('inventories'));
});

// Ensembles.Members
Breadcrumbs::for('members', function (BreadcrumbTrail $trail) {
    $trail->parent('ensembles');
    $trail->push('Members', route('members'));
});

// Ensembles.Members.Create
Breadcrumbs::for('member create', function (BreadcrumbTrail $trail) {
    $trail->parent('members');
    $trail->push('Add Member', route('members'));
});

// Ensembles.Members.Edit
Breadcrumbs::for('member edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('members');
    $trail->push('Edit Member', route('members', ['member' => $id]));
});

//Estimate
Breadcrumbs::for('estimate', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('participation active', $id);
    $trail->push('Estimate', route('estimate', $id));
});

//Event.Create
Breadcrumbs::for('new event', function (BreadcrumbTrail $trail) {
    $trail->parent('my events');
    $trail->push('New', route('event.create'));
});

//Event.Edit
Breadcrumbs::for('event edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('my events');
    $trail->push('Edit', route('event.edit', [$id]));
});

//Events.Dashboard
Breadcrumbs::for('events dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Events Dashboard', route('events.dashboard'));
});

//Events.Manage.Table
Breadcrumbs::for('my events', function (BreadcrumbTrail $trail) {
    $trail->parent('events dashboard');
    $trail->push('My Events', route('events.manage'));
});

//Events.Participation.Dashboard
Breadcrumbs::for('events participation', function (BreadcrumbTrail $trail) {
    $trail->parent('events dashboard');
    $trail->push('Event Participation', route('events.participation.table'));
});

//Judge Assignments
Breadcrumbs::for('judge assignment', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Judge Assignment', route('judgeAssignment'));
});

//Adjudication
Breadcrumbs::for('adjudication', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('participation active', $id);
    $trail->push('Adjudication', route('adjudication', $id));
});

//Obligations
Breadcrumbs::for('obligations', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('participation active', $id);
    $trail->push('Obligations', route('obligations', $id));
});

//Participation.Dashboard
Breadcrumbs::for('participation active', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('events participation', $id);
    $trail->push('Participation Dashboard', route('participation.dashboard', $id));
});

//Pitchfiles
Breadcrumbs::for('pitchfiles', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('participation active', $id);
    $trail->push('Pitch Files', route('pitchFiles', $id));
});

// Profile
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Profile', route('profile.edit'));
});

// Founder
Breadcrumbs::for('founder page', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Founder Page', route('founder'));
});

// Schools
Breadcrumbs::for('schools', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Schools', route('schools'));
});

//School.create
Breadcrumbs::for('new school', function (BreadcrumbTrail $trail) {
    $trail->parent('schools');
    $trail->push('New', route('school.create'));
});

//School.edit
Breadcrumbs::for('school edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('schools');
    $trail->push('Edit', route('school.edit', ['school' => $id]));
});

// Students
Breadcrumbs::for('students', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Students', route('students'));
});

//Student.create
Breadcrumbs::for('new student', function (BreadcrumbTrail $trail) {
    $trail->parent('students');
    $trail->push('New', route('student.create'));
});

//Student.edit
Breadcrumbs::for('student edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('students');
    $trail->push('Edit', route('student.edit', ['student' => $id]));
});

//Student.comms.edit
Breadcrumbs::for('student comms edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('student edit', $id);
    $trail->push('Comms Edit', route('student.comms.edit', ['student' => $id]));
});

//Student.ec.edit
Breadcrumbs::for('student ec edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('student edit', $id);
    $trail->push('EC Edit', route('student.ec.edit', ['student' => $id]));
});

//Student.reset
Breadcrumbs::for('student reset password', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('student edit', $id);
    $trail->push('Reset Password', route('student.reset', ['student' => $id]));
});

//Tabroom:close version
Breadcrumbs::for('tabroom close version', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Tabroom Close Version', route('version.tabroom.close'));
});

//Tabroom:cutoffs
Breadcrumbs::for('tabroom cutoff', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Tabroom Cutoff', route('version.tabroom.cutoff'));
});

//Tabroom:reports
Breadcrumbs::for('tabroom reports', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Tabroom reports', route('version.tabroom.reports'));
});

//Tabroom:scoring
Breadcrumbs::for('tabroom scoring', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Tabroom Scoring', route('version.tabroom.scoring'));
});

//Tabroom:tracking
Breadcrumbs::for('tabroom tracking', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Tabroom Tracking', route('version.tabroom.tracking'));
});

//Versions.Dashboard (note: MULTIPLE versions)
Breadcrumbs::for('versions dashboard', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('my events');
    $trail->push('Versions Dashboard', route('versions.index', ['event' => $id]));
});

//Versions.Table (note: MULTIPLE versions)
Breadcrumbs::for('versions table', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('my events', $id);
    $trail->push('Versions Table', route('versions.index', ['event' => $id]));
});

//Version.Configs
Breadcrumbs::for('version configs edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Version Configs Edit', route('version.configs'));
});

//Version.Dates
Breadcrumbs::for('version dates edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Version Dates Edit', route('version.dates'));
});

//Version.Participants
Breadcrumbs::for('version participants', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Version Participants', route('version.participants'));
});

//Version.PitchFiles
Breadcrumbs::for('version pitch files', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Version Pitch Files', route('version.pitchFiles'));
});

//Version.Roles
Breadcrumbs::for('version roles', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Version Roles', route('version.roles'));
});

//Version.Profile
Breadcrumbs::for('version profile', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('versions table', $id);
    $trail->push('Version Profile', route('version.create', ['event' => $id]));
});

//Version.Edit Profile
Breadcrumbs::for('version edit profile', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Version Edit Profile', route('version.edit', ['event' => $id]));
});

//Version.Dashboard (note: SINGLE version)
Breadcrumbs::for('version dashboard', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('my events'); //s/b versions dashboard
    $trail->push('Version Dashboard', route('version.show', ['version' => $id]));
});

//Versions.Reports
Breadcrumbs::for('version reports', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Version Reports', route('version.reports'));
});
//Version.Reports.ObligatedTeachers
Breadcrumbs::for('obligated teachers', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version reports', $id);
    $trail->push('Obligated Teachers', route('version.reports.obligatedTeachers'));
});
//Version.Reports.ParticipatingSchools
Breadcrumbs::for('participating schools', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version reports', $id);
    $trail->push('Participating Schools', route('version.reports.participatingSchools'));
});
//Version.Reports.ParticipatingStudents
Breadcrumbs::for('participating students', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version reports', $id);
    $trail->push('Participating Students', route('version.reports.participatingStudents'));
});
//Version.Reports.ParticipatingTeachers
Breadcrumbs::for('participating teachers', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version reports', $id);
    $trail->push('Participating Teachers', route('version.reports.participatingTeachers'));
});
//Version.Reports.Student Counts
Breadcrumbs::for('student counts', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version reports', $id);
    $trail->push('Student Counts', route('version.reports.studentCounts'));
});
//Version.Reports.Adjudication.backupPaper
Breadcrumbs::for('adjudication paper backup', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version reports', $id);
    $trail->push('Paper Backup', route('version.reports.backupPaper'));
});
//Version.Reports.Adjudication.backupCsv
Breadcrumbs::for('adjudication csv backup', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version reports', $id);
    $trail->push('Csv Backup', route('version.reports.backupCsv'));
});

//Version.Reports.Adjudication.monitorChecklist
Breadcrumbs::for('adjudication monitor checklist', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version reports', $id);
    $trail->push('Monitor Checklist', route('version.reports.monitorChecklist'));
});

//Version.Reports.Adjudication.monitorChecklist
Breadcrumbs::for('registration cards', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version reports', $id);
    $trail->push('Registration Cards', route('version.reports.registrationCards'));
});

//Version.Scoring
Breadcrumbs::for('version scoring', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Version Scoring', route('version.scoring'));
});

//Versions.StudentTransfer
Breadcrumbs::for('student transfer', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Student Transfer', route('studentTransfer'));
});

//Versions.TimeslotAssignment
Breadcrumbs::for('timeslot assignment', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Timeslot Assignment', route('timeslotAssignment'));
});

// Unknown = ViewData not found for the calling controller::method
Breadcrumbs::for('unknown', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Unknown', route('home'));
});

// Home > Blog
//Breadcrumbs::for('blog', function (BreadcrumbTrail $trail) {
//    $trail->parent('home');
//    $trail->push('Blog', route('blog'));
//});

// Home > Blog > [Category]
//Breadcrumbs::for('category', function (BreadcrumbTrail $trail, $category) {
//    $trail->parent('blog');
//    $trail->push($category->title, route('category', $category));
//});
