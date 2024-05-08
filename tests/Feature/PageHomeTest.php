<?php

use function Pest\Laravel\get;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('shows domain names', function () {

    $user = \App\Models\User::factory()->create();

    auth()->login($user);

    get(route('home'))
        ->assertSeeText([
            'Schools',
            'Students',
            'Events'
        ]);
});

it('checks if schools page is available', function () {

    get('/schools')
        ->assertOk()
        ->assertSeeText('Schools');
});

it('checks if students page is available', function () {
//
//    get('/students')
//        ->assertOk()
//        ->assertSeeText('Students');
});
//
it('checks if events page is available', function () {
//
//    get('/events')
//        ->assertOk()
//        ->assertSeeText('Events');
});


