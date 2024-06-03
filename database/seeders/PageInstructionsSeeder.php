<?php

namespace Database\Seeders;

use App\Models\PageInstruction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageInstructionsSeeder extends Seeder
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
                'header' => 'new school',
                'instructions' => '<p class="">Every user of TheDirectorsRoom.com must have at least one school attached to their profile.
 <br />Use this page to add a new school to your Schools roster.<br />All required fields are marked with a red asterisk "<span style="color: red;">*</span>".<br />
 </p>
 <p>Please use the <strong>full name</strong> of your school and avoid the use of abbreviations and initials.
 </p>
 <p><strong>County</strong> name is often used by events to identify potential participants and by the system to identify potential events of interest for you.
 </p>
 <p><strong>Grades</strong> are used to determine which events and students to display.  Please ensure that you click the appropriate checkboxes for:
 <ul class="">
 <li class="">the grades <strong>taught</strong> at the school, and</li>
 <li class="">the grades <strong><em>you teach</em></strong> at the school.</li>
 </ul>
 </p>
 <p>
 The system will send a verification email to your <strong>work email address</strong> whenever added or changed, and at least annually to ensure your active status at the school and to safeguard the privacy of student-entered information. Commercial emails (gmail, hotmail, etc.) are automatically <strong>unverified</strong> for these purposes.
 </p>'
            ],
            [
                'header' => 'home',
                'instructions' => '<p>Home Page</p>',
            ],
            [
                'header' => 'schools',
                'instructions' => '<p>Schools Page</p>
<p>Click the icons under the "active?" column to change your active school status between "yes" and "no".
</p>
<p>Click the "Edit" button to edit the school information on that row.</p>
<p>Click the "Remove" button to remove a school from your roster.<br />
If you only have one school, the "Remove" button will not display.  All users of TheDirectorsRoom.com must have at least one school on their roster.<br />
A school should be removed if it was added by mistake.  If you no longer work at a school on the roster, use the links under the "active?" column to change your status at that school.<br />
If you remove a school by mistake, please click the "+" button to re-add that school to your roster.
</p>',
            ],
            [
                'header' => 'school edit',
                'instructions' => '<p class="">Use this page to edit a school on your Schools roster.<br />All required fields are marked with a red asterisk "<span style="color: red;">*</span>".<br />
 </p>
 <p>Please use the <strong>full name</strong> of your school and avoid the use of abbreviations and initials.
 </p>
 <p>The <strong>county</strong> is often used by events to identify potential participants and by the system to identify potential events of interest for you.
 </p>
 <p><strong>Grades</strong> are used to determine which events and students to display.  Please ensure that you click the appropriate checkboxes for:
        <ul class="">
 <li class="">the grades <strong>taught</strong> at the school, and</li>
 <li class="">the grades <strong><em>you teach</em></strong> at the school.</li>
 </ul>
 </p>
 <p>
    The system will send a verification email to your <strong>work email address</strong> whenever the email is added or changed. <br />
A verification email will also be sent at least annually to ensure your active status at the school and to safeguard the privacy of student-entered information. <br />
Please check your <strong>spam folder</strong> if expected system-created emails are not being received.<br />
Commercial emails (gmail, hotmail, etc.) are automatically <strong>unverified</strong> for these purposes.  <br />
<u>An unverified email will preclude you from viewing any student-entered information.</u>
 </p>'
            ],
            [
                'header' => 'students',
                'instructions' => '<p>Students Table Page</p><p>Students Table page instructions...</p>'
            ],
            [
                'header' => 'new student',
                'instructions' => '<p>Add Student Page</p><p>Add Student page instructions...</p>'
            ],
            [
                'header' => 'student edit',
                'instructions' => '<p>Students Edit Page</p><p>Student Edit page instructions...</p>'
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            PageInstruction::create(
                [
                    'header' => $seed['header'],
                    'instructions' => $seed['instructions'],

                ]
            );
        }
    }
}
