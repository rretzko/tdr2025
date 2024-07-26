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
                'href' => 'events/dashboard',
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
            [
                'header' => 'version dashboard',
                'color' => 'blue',
                'description' => '<p>Includes basic information about the version: name, short name status, etc.</p>',
                'heroicon' => 'heroicons.identification',
                'href' => '/version/profile',
                'label' => 'version profile',
                'orderBy' => 1,
            ],
            [
                'header' => 'version dashboard',
                'color' => 'green',
                'description' => '<p>Includes detailed configurations which determine which event options are available to your member participants and their students.</p>',
                'heroicon' => 'heroicons.gear',
                'href' => '/version/configs',
                'label' => 'configurations',
                'orderBy' => 2,
            ],
            [
                'header' => 'version dashboard',
                'color' => 'red',
                'description' => '<p>Define the dates that your event version is opened and closed to your member participants and their students.</p>',
                'heroicon' => 'heroicons.calendar',
                'href' => '/version/dates',
                'label' => 'dates',
                'orderBy' => 3,
            ],
            [
                'header' => 'version dashboard',
                'color' => 'yellow',
                'description' => '<p>Add and remove your member for this event version participation.</p>',
                'heroicon' => 'heroicons.people',
                'href' => '/version/participants',
                'label' => 'participants',
                'orderBy' => 4,
            ],
            [
                'header' => 'version dashboard',
                'color' => 'indigo',
                'description' => '<p>Assign version management roles to your previously selected participants, including event owner(s), registration manager(s), tab room participants, etc.</p>',
                'heroicon' => 'heroicons.briefcase',
                'href' => '/version/roles',
                'label' => 'event version roles',
                'orderBy' => 5,
            ],
            [
                'header' => 'version dashboard',
                'color' => 'green',
                'description' => '<p>Add, remove, and order your pitch files for availability to your member teachers and their students.</p>',
                'heroicon' => 'heroicons.sixteenthNotes',
                'href' => '/version/pitchFiles',
                'label' => 'pitch files',
                'orderBy' => 6,
            ],
            [
                'header' => 'version dashboard',
                'color' => 'red',
                'description' => '<p>Scoring.</p>',
                'heroicon' => 'heroicons.star',
                'href' => '/version/scoring',
                'label' => 'scoring',
                'orderBy' => 7,
            ],
            [
                'header' => 'version dashboard',
                'color' => 'yellow',
                'description' => '<p>Attachments and PDFs</p>',
                'heroicon' => 'heroicons.documentCheck',
                'href' => '/version/attachments',
                'label' => 'attachments',
                'orderBy' => 8,
            ],
            [
                'header' => 'participation active',
                'color' => 'green',
                'description' => '<p>Manage your student\'s registration for the event.</p>
<p>Only students matching the event\'s grade requirements will be included in this roster.</p>',
                'heroicon' => 'heroicons.mortarBoard',
                'href' => '/candidates',
                'label' => 'eligible student roster',
                'orderBy' => 1,
            ],
            [
                'header' => 'participation active',
                'color' => 'indigo',
                'description' => '<p>Click to review the obligations you accepted
for participation in this event.</p>',
                'heroicon' => 'heroicons.documentCheck',
                'href' => '/obligations',
                'label' => 'teacher obligation',
                'orderBy' => 2,
            ],
            [
                'header' => 'participation active',
                'color' => 'yellow',
                'description' => '<p>View the pitch files for this event</p>',
                'heroicon' => 'heroicons.sixteenthNotes',
                'href' => '/pitchFiles',
                'label' => 'pitch files',
                'orderBy' => 3,
            ],
            [
                'header' => 'participation active',
                'color' => 'red',
                'description' => '<p>View the estimate/invoice form and PayPal
payment option (if available) for this event.</p>',
                'heroicon' => 'heroicons.tableCells',
                'href' => '/estimate',
                'label' => 'estimate/Invoice form',
                'orderBy' => 4,
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
