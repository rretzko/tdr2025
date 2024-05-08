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

it('returns a successful home page response from auth', function () {

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
        ->assertOK(); //200 response
});

