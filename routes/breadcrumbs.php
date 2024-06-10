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
Breadcrumbs::for('ensemble.create', function (BreadcrumbTrail $trail) {
    $trail->parent('ensembles');
    $trail->push('Add Ensemble', route('ensemble.create'));
});

// Ensemble.edit
Breadcrumbs::for('ensemble.edit', function (BreadcrumbTrail $trail, int $id) {
    $trail->parent('ensembles');
    $trail->push('Edit Ensemble', route('ensemble.ensemble'));
});

// Profile
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Profile', route('profile.edit'));
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
