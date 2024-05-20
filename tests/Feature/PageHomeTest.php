<?php

use function Pest\Laravel\get;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('shows domain names', function () {

    $teacher = \App\Models\Schools\Teacher::factory()->create();
    $school = \App\Models\Schools\School::factory()->create();
    \App\Models\Schools\SchoolTeacher::factory()->create(
        [
            'school_id' => $school->id,
            'teacher_id' => $teacher->id,
        ]
    );

    auth()->login($teacher->user);

    get(route('home'))
        ->assertSeeText([
            'Schools',
            'Students',
            'Events'
        ]);
});

it('checks if schools page is available', function () {

    $this->withoutExceptionHandling();

    $teacher = \App\Models\Schools\Teacher::factory()->create();
    $school = \App\Models\Schools\School::factory()->create();
    \App\Models\Schools\SchoolTeacher::factory()->create(
        [
            'school_id' => $school->id,
            'teacher_id' => $teacher->id,
        ]
    );

    auth()->login($teacher->user);;

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


