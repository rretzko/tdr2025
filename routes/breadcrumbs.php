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
Breadcrumbs::for('school edit', function (BreadcrumbTrail $trail) {
    $trail->parent('schools');
    $trail->push('Edit', route('school.edit', ['school' => 1]));
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
