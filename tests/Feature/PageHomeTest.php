<?php

use App\Models\ViewPage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use function Pest\Laravel\get;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('shows domain names', function () {

    ViewPage::factory()->create(
        [
            'controller' => 'HomeController',
            'method' => '__invoke',
            'page_name' => 'dashboard',
            'header' => 'home'
        ],
    );

    \App\Models\ViewCard::factory()->create(
        [
            'header' => 'home',
            'label' => 'schools'
        ]
    );

    \App\Models\ViewCard::factory()->create(
        [
            'header' => 'home',
            'label' => 'students'
        ],
    );

    \App\Models\ViewCard::factory()->create(
        [
            'header' => 'home',
            'label' => 'events'
        ],
    );

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

    //$this->withoutExceptionHandling();

    ViewPage::factory()->create(
        [
            'controller' => 'SchoolsController',
            'method' => '__invoke',
            'page_name' => 'dashboard',
            'header' => 'home'
        ],
    );

    \App\Models\ViewCard::factory()->create(
        [
            'header' => 'home',
            'label' => 'schools'
        ]
    );

    $teacher = \App\Models\Schools\Teacher::factory()->create();
    $school = \App\Models\Schools\School::factory()->create();
    \App\Models\Schools\SchoolTeacher::factory()->create(
        [
            'school_id' => $school->id,
            'teacher_id' => $teacher->id,
        ]
    );

    auth()->login($teacher->user);

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


