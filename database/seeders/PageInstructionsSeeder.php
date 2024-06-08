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
                'instructions' => '<p>Students Table Page</p>
<p>Students are displayed in name-alpha order.</p>
<p>If you have multiple schools and have previously selected a school, that school\'s students will be displayed by default.  Schools can be changed by clicking on the "Filter" button.</p>
<p>Sortable columns are displayed with up/down arrows in the column headers</p>
<p>You can search for name, email, class, and voice part by entering a value in the "Search" box.</p>
<p>You can filter the results by clicking on the "Filter" button.  Filtering options are school (if you have multiple schools), class-of, and voice part.</p>'
            ],
            [
                'header' => 'new student',
                'instructions' => '<p>Add Student Page</p>
<p>Use this page to add a new student to your Students roster.<br />
<br />All required fields are marked with a red asterisk "<span class="text-red-600">*</span>".</p>
<p>The system will automatically check to see if the student information is duplicated.  If a likely duplicate is found, you will be given the option to "Continue" adding the student record, or "Cancel" to return to the form.  The entered student information will be maintained and you will not lose any of your work.</p>
<p>The email on this page is the email address the student will use to log into StudentFolder.info. It must be unique and not shared with any other student or director. <b>Whenever possible, it is recommended that students use a commercial email address (google, icloud, hotmail, etc.).</b></p>
<p>If your student does not have an email address, or uses a shared family email address, please create a proxy for the student (example: studentName@fake.com).  The student will not be able to receive system-generated emails to this fake address, but will be able to log into StudentFolder.info to maintain their information.</p>
<p>Please note that students using a school email address will similarly be unable to receive system-generated emails (ex. password reset email) due to school email servers blocking external emails.  In these cases, students will be directed to contact you for any password reset needs.  Student passwords can be reset from the "Students" page by clicking  the "Edit" button on the student\'s row.</p>
<p>Phone information can be entered in any format and will be re-formatted as "(###) ###-#### x###" for a consistent display.</p>
<p>Additional student information (address, emergency contact) may be entered from the "Students" page by clicking on the "Edit" button.</p>'
            ],
            [
                'header' => 'student edit',
                'instructions' => '<p>Students Edit Page</p>
<p>
This page is divided into four tabs and allows you to edit your student information.  The tabs are:
<ul>
<li><strong>bio</strong>: containing name, etc.</li>
<li><strong>comms</strong>: containing email, phone numbers, and home address,</li>
<li><strong>emergency contact</strong>: containing the student emergency contact information, and </li>
<li><strong>password reset</strong>: a single button allowing you to reset the student\'s password to their lower-case email address.</li>
</ul>
</p>
<p>Please note that there is no "Submit" button.  Each section of the form will display a "success" message when a field is changed.</p>'
            ],
            [
                'header' => 'student comms edit',
                'instructions' => '<p>Student Communications Edit page</p>
<p>Use this page to edit the fields used to communicate with your students: email, phone, or snail mail.</p>
<p>Note: There are no fields required by the system although some events may conditionally require this information for student registration.</p>'
            ],
            [
                'header' => 'student ec edit',
                'instructions' => '<p>Student Emergency Contact Edit page</p>
<p>Use this page to add/edit your student\'s Emergency Contact information.</p>
<p>Your student may have one or many emergency contacts listed.</p>
<p>Note: The system does not require a student to have an Emergency Contact although some events may conditionally require this information for student registration.</p>'
            ],
            [
                'header' => 'student reset password',
                'instructions' => '<p>Student Reset Password page</p>
<p>Use this page to reset your student\'s password.</p>
<p>Clicking the button on this page will reset the password for <b>StudentFolder.info</b> to the lower-case version of your student\'s email address.
Once logged into StudentFolder.info, the student should update their password using the "Change Password" link.</p>'
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