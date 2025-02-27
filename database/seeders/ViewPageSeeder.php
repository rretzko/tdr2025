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
            [
                'EnsembleController',
                'index',
                'livewire',
                'ensembles'
            ],
            [
                'EnsembleController',
                'edit',
                'livewire',
                'ensemble edit'
            ],
            [
                'EnsembleController',
                'create',
                'livewire',
                'ensemble create'
            ],
            [
                'AssetController',
                'index',
                'livewire',
                'assets'
            ],
            [
                'MemberController',
                'index',
                'livewire',
                'members'
            ],
            [
                'MemberController',
                'edit',
                'livewire',
                'member edit'
            ],
            [
                'MemberController',
                'create',
                'livewire',
                'member create'
            ],
            [
                'InventoryController',
                'create',
                'livewire',
                'inventory new'
            ],
            [
                'InventoryController',
                'edit',
                'livewire',
                'inventory edit'
            ],
            [
                'EventsDashboardController',
                '__invoke',
                'dashboard',
                'events dashboard',
            ],
            [
                'EventsManageController',
                '__invoke',
                'livewire',
                'my events',
            ],
            [
                'EventManageCreateController',
                '__invoke',
                'livewire',
                'new event',
            ],
            [
                'EventManageEditController',
                '__invoke',
                'livewire',
                'event edit',
            ],
            [
                'VersionProfileController',
                'create',
                'livewire',
                'version profile',
            ],
            [
                'VersionProfileController',
                'edit',
                'livewire',
                'version edit profile',
            ],
            [
                'VersionController',
                'show',
                'dashboard',
                'version dashboard',
            ],
            [
                'VersionsController',
                'index',
                'livewire',
                'versions table',
            ],
            [
                'VersionConfigController',
                'edit',
                'livewire',
                'version configs edit',
            ],
            [
                'VersionDateController',
                'edit',
                'livewire',
                'version dates edit',
            ],
            [
                'VersionParticipantController',
                'index',
                'livewire',
                'version participants',
            ],
            [
                'VersionRoleController',
                '__invoke',
                'livewire',
                'version roles',
            ],
            [
                'VersionPitchFileController',
                '__invoke',
                'livewire',
                'version pitch files',
            ],
            [
                'VersionScoringController',
                '__invoke',
                'livewire',
                'version scoring',
            ],
            [
                'EventsParticipationController',
                '__invoke',
                'dashboard',
                'events participation',
            ],
            [
                'ParticipationActiveController',
                '__invoke',
                'dashboard',
                'participation dashboard',
            ],
            [
                'ParticipationResultsController',
                '__invoke',
                'livewire',
                'participation results',
            ],
            [
                'CandidateController',
                '__invoke',
                'livewire',
                'candidates',
            ],
            [
                'ObligationController',
                '__invoke',
                'livewire',
                'obligations',
            ],
            [
                'PitchFileController',
                '__invoke',
                'livewire',
                'pitchfiles',
            ],
            [
                'EstimateController',
                '__invoke',
                'livewire',
                'estimate',
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
