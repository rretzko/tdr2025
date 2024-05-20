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
//Schools.create
Breadcrumbs::for('new school', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('New', route('school.create'));
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
