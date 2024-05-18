<?php

use App\Models\ViewPage;
use function Pest\Laravel\get;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

//WELCOME PAGE
it('returns a successful welcome page response', function () {
    get(route('welcome'))
        ->assertOK(); //200 response
});

//HOME PAGE
it('returns a found home page response from guest', function () {
    get(route('home'))
        ->assertFound(); //302 response
});

it('returns a redirect home page response from guest', function () {
    get(route('home'))
        ->assertRedirect(); //307 response
});

it('returns 403 if auth user is not a teacher', function () {

    ViewPage::create(
        [
            'controller' => 'HomeController',
            'method' => '__invoke',
            'page_name' => 'dashboard',
            'header' => 'home',
        ]
    );

    $user = \App\Models\User::factory()->create();

    auth()->login($user);

    get(route('home'))
        ->assertForbidden() //403 response
        ->assertSeeText('You must be a teacher to use TheDirectorsRoom.com');

});

it('returns 403 if auth user is a teacher without a work email', closure: function () {

    ViewPage::create(
        [
            'controller' => 'HomeController',
            'method' => '__invoke',
            'page_name' => 'dashboard',
            'header' => 'home',
        ]
    );

    $schoolTeacher = \App\Models\Schools\SchoolTeacher::factory()->create(
        [
            'email' => null,
        ]
    );

    auth()->login(\App\Models\Schools\SchoolTeacher::find(1)->teacher->user);

    get(route('home'))
        ->assertForbidden() //403 response
        ->assertSeeText('You must be a teacher to use TheDirectorsRoom.com');

});

it('returns 403 if auth user is a teacher with UNVERIFIED work email', closure: function () {

    ViewPage::create(
        [
            'controller' => 'HomeController',
            'method' => '__invoke',
            'page_name' => 'dashboard',
            'header' => 'home',
        ]
    );

    $schoolTeacher = \App\Models\Schools\SchoolTeacher::factory()->create(
        [
            'email_verified_at' => null,
        ]
    );

    auth()->login(\App\Models\Schools\SchoolTeacher::find(1)->teacher->user);

    get(route('home'))
        ->assertForbidden() //403 response
        ->assertSeeText('You must be a teacher to use TheDirectorsRoom.com');

});

it('returns a successful home page response from auth', function () {

    //$this->withoutExceptionHandling();

    $schoolTeacher = \App\Models\Schools\SchoolTeacher::factory()->create();

    auth()->login($schoolTeacher->teacher->user);

    get(route('home'))
        ->assertOK(); //200 response
});

it('returns successful school-create page if auth without linked school', function () {

    $user = \App\Models\User::factory()->create();

    auth()->login($user);

    get(route('school.create'))
        ->assertOK(); //200 response
});

