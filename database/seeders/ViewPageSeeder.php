<?php

namespace Database\Seeders;

use App\Models\ViewPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViewPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seeds = [
            [
                'HomeController',
                '__invoke',
                'dashboard',
                'home'
            ],
            [
                'SchoolController',
                'create',
                'livewire',
                'new school'
            ],
            [
                'SchoolsController',
                '__invoke',
                'livewire',
                'schools'
            ],
            [
                'SchoolController',
                'edit',
                'livewire',
                'school edit',
            ],
            [
                'StudentsController',
                '__invoke',
                'livewire',
                'students'
            ],
            [
                'StudentController',
                'create',
                'livewire',
                'new student',
            ],
            [
                'StudentController',
                'edit',
                'livewire',
                'student edit',
            ],
            [
                'StudentCommunicationsController',
                'edit',
                'livewire',
                'student comms edit'
            ],
            [
                'StudentEmergencyContactController',
                'edit',
                'livewire',
                'student ec edit'
            ],
            [
                'StudentResetPasswordController',
                '__invoke',
                'livewire',
                'student reset password'
            ],
        ];

        foreach ($seeds as $seed) {

            ViewPage::create(
                [
                    'controller' => $seed[0],
                    'method' => $seed[1],
                    'page_name' => $seed[2],
                    'header' => $seed[3],
                ]
            );
        }
    }
}
