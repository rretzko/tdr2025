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
                'table',
                'schools'
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
