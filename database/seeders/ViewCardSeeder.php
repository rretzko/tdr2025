<?php

namespace Database\Seeders;

use App\Models\ViewCard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViewCardSeeder extends Seeder
{
    private array $seeds;

    public function __construct()
    {
        $this->seeds = $this->buildSeeds();
    }

    private function buildSeeds(): array
    {
        return [
            [
                'header' => 'home',
                'color' => 'indigo',
                'description' => '<p>Add/Edit your schools including <b>ensembles</b>, <b>libraries</b>, and even your private studio if you have one!</p><p>You can also grant/remove co-teacher access to your students.</p>',
                'heroicon' => 'heroicons.building',
                'href' => 'schools',
                'label' => 'schools',
                'orderBy' => 1,
            ],
            [
                'header' => 'home',
                'color' => 'green',
                'description' => '<p>Add and edit your students\' records.</p>',
                'heroicon' => 'heroicons.mortarBoard',
                'href' => 'students',
                'label' => 'students',
                'orderBy' => 2,
            ],
            [
                'header' => 'home',
                'color' => 'blue',
                'description' => '<p>Add and edit your performance ensembles.</p>',
                'heroicon' => 'heroicons.userGroup',
                'href' => 'ensembles',
                'label' => 'ensembles',
                'orderBy' => 3,
            ],
            [
                'header' => 'home',
                'color' => 'yellow',
                'description' => '<p>Add and edit your music libraries.</p>',
                'heroicon' => 'heroicons.bookOpen',
                'href' => 'libraries',
                'label' => 'libraries',
                'orderBy' => 4,
            ],
            [
                'header' => 'home',
                'color' => 'red',
                'description' => '<p>Update your student registration information for an upcoming auditioned event (ex. Region, All-State, etc.).</p><p>Open adjudication pages (when available).</p><p>Even create and manage an event of your own!</p>',
                'heroicon' => 'heroicons.calendar',
                'href' => 'events',
                'label' => 'events',
                'orderBy' => 5,
            ],
            [
                'header' => 'events dashboard',
                'color' => 'green',
                'description' => '<p>Add information and registrants to events in which you are participating.</p>',
                'heroicon' => 'heroicons.calendar',
                'href' => 'participation/dashboard',
                'label' => 'event participation',
                'orderBy' => 1,
            ],
            [
                'header' => 'events dashboard',
                'color' => 'yellow',
                'description' => '<p>This card will activate if you are selected to judge an audition.</p>',
                'heroicon' => 'heroicons.scales',
                'href' => 'adjudication',
                'label' => 'adjudication',
                'orderBy' => 2,
            ],
            [
                'header' => 'events dashboard',
                'color' => 'red',
                'description' => '<p>Add new or manage existing events.</p>',
                'heroicon' => 'heroicons.gear',
                'href' => 'manage',
                'label' => 'manage events',
                'orderBy' => 3,
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            ViewCard::create(
                [
                    'header' => $seed['header'],
                    'color' => $seed['color'],
                    'description' => $seed['description'],
                    'heroicon' => $seed['heroicon'],
                    'href' => $seed['href'],
                    'label' => $seed['label'],
                    'order_by' => $seed['orderBy'],
                ]
            );
        }
    }
}
