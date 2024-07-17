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
    $trail->push('Edit', route('student.edit', ['school_student' => $id]));
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

//Versions.Dashboard (note: MULTIPLE versions)
Breadcrumbs::for('versions dashboard', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('my events');
    $trail->push('Versions Dashboard', route('versions.index', ['event' => $id]));
});

//Versions.Table (note: MULTIPLE versions)
Breadcrumbs::for('versions', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('my events');
    $trail->push('Versions Table', route('versions.index', ['event' => $id]));
});

//Version.Configs
Breadcrumbs::for('version configs edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('version dashboard', $id);
    $trail->push('Version Configs Edit', route('version.configs'));
});

//Version.Profile
Breadcrumbs::for('version profile', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('versions');
    $trail->push('Version Profile', route('version.create', ['event' => $id]));
});

//Version.Edit Profile
Breadcrumbs::for('version edit profile', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('my events');
    $trail->push('Version Edit Profile', route('version.edit', ['event' => $id]));
});

//Version.Dashboard (note: SINGLE version)
Breadcrumbs::for('version dashboard', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('my events'); //s/b versions dashboard
    $trail->push('Version Dashboard', route('version.show', ['version' => $id]));
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
