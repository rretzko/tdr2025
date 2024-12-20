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
<p>The Students Table page displays your students with robust display, search, filter, and sort features:
<ul>
<li><strong>Records Per Page</strong>: Select from the drop-down box to increase or decrease the number of pages displayed per page.</li>
<li><strong>Search</strong>: Display records that match the value in the "Search" box for any part of the student\'s name. Note: capitalization is disregarded in the search so <strong><i>DiGreggorio</i></strong> is the same as <strong><i>digreggorio</i></strong>.</li>
<li><strong>Export</strong>: Click the Export button to export <strong>ALL</strong> of your student records for your analysis and backup. The export contains all student information from the bio, comms, and emergency contact tabs.  Note that the student\'s information will appear on multiple rows if the student has multiple Emergency Contacts.</li>
<li><strong>Filters</strong>: You will find filtering options on the left-hand side of the table.  Checking and unchecking boxes will automatically trigger an update of the table to reflect the options chosen.
<ul>
<li>If you teach in multiple schools, a <strong>Schools</strong> filter will display the abbreviation for those schools.  Clicking/unclicking the appropriate checkbox will include/exclude the display of students from those schools.</li>
<li>The <strong>Class Of</strong> filter contains all years matching those of your students in the system.</li>
<li>The <strong>Class Of</strong> filter contains two aggregate settings: <strong>current</strong> and <strong>alum</strong>.
<ul>
<li>The <strong>current</strong> option will select the current senior year and all years later than the current senior year.</li>
<li>The <strong>alum</strong> option will select all years earlier than the current senior year.</li>
</ul>
</li>
<li>The <strong>Voice Parts</strong> filter contains all voice parts matching those of your students in the system.</li>
</li>
</ul>
<li><strong>Sorting</strong>: The table column headers: name, class of, and voice part may be clicked to sort the table in ascending or descending order by those columns.
<ul>
<li>Up- and down-arrows will display on the selected column to indicate the current direction of the sort.</li>
<li>Voice parts are sorted in score-order rather than alpha-order, i.e. soprano-to-bass or bass-to-soprano.
</ul>
</li>
</ul>
</p>
<p><u>Please note that the system "remembers" your settings.</u>  If you leave and later return to the Students table, the settings you last used will be used for your return display.</p>'

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
<li><strong>bio</strong>: containing name, etc.
<ul>
<li>Students may be "active" in only ONE school at a time.</li>
</ul>
</li>
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
<p>Clicking the button on this page will reset the student\'s password for <b>StudentFolder.info</b> to the lower-case version of your student\'s email address.
    Once logged into StudentFolder.info, the student should update their password using the "Change Password" link.</p>'
            ],
            [
                'header' => 'ensembles',
                'instructions' => '<p>Ensembles Table page</p>
<p>This page contains a table of your active and inactive ensembles.</p>'
            ],
            [
                'header' => 'ensemble create',
                'instructions' => '<p>Add Ensemble page</p>
<p>Use this page to add an ensemble to your roster.</p>'
            ],
            [
                'header' => 'ensemble edit',
                'instructions' => '<p>Edit Ensemble page</p>
<p>Use this page to edit an ensemble on your roster.</p>'
            ],
            [
                'header' => 'assets',
                'instructions' => '<p>Assets Table page instructions</p><p>This page contains all assets available to your ensembles.</p>'
            ],
            [
                'header' => 'members',
                'instructions' => '<p>Members Table page instructions</p><p>Use this page to review your ensemble member\'s information.</p>'
            ],
            [
                'header' => 'member create',
                'instructions' => '<p>School Ensemble Member create page instructions</p>
<p>Use this page to add a school ensemble\'s member information</p>'
            ],
            [
                'header' => 'member edit',
                'instructions' => '<p>School Ensemble Member edit page instructions</p>
<p>Use this page to edit a school ensemble\'s member information and to assign or remove assets from the ensemble member.</p>
<p>There are four editable fields regarding the member\'s biographic information:
<ul>
<li><strong>School Year</strong>: The school year for which you are entering information.  This will typically be the current school year, but might represent previous years, or even the <i>next</i> school year if you\'re planning ahead!
<ul>
<li>The field expects a four-character number representing the end of the school year, for example: the 2024-25 school year is represented by 2025.</li>
</ul>
</li>
<li><strong>Voice Part</strong>: Select the voice part from the drop-down list.</li>
<li><strong>Office</strong>: This defaults to "member" but you may select any of the values from the drop-down box.
<ul>
<li>If you need an office that\'s not listed, drop me an email explaining your needs at <a href="mailto:rick@mfrholdings.com?subject=New office title needed&body=Hi Rick - " class="text-blue-500">rick@mfrholdings.com</a>!</li>
</ul>
</li>
<li><strong>Status</strong>: This defaults to "active" but you may select any of the values from the drop-down box.
<ul>
<li>If you need an office that\'s not listed, drop me an email explaining your needs at <a href="mailto:rick@mfrholdings.com?subject=New school ensemble member status needed&body=Hi Rick - "
 class="text-blue-500">rick@mfrholdings.com</a>!</li>
</ul>
</li>
</ul>
</p>
<p>If assets have been assigned to the ensemble (ex. folders, gowns, tuxes, etc.), you may assign these assets from your inventory to the ensemble member.  If an asset is already assigned, that asset information will be displayed with a button to remove the asset from the member\'s record.</p>'
            ],
            [
                'header' => 'inventories',
                'instructions' => '<p>Inventory table page instructions</p>
<p>Inventory table page instructions...</p>'
            ],
            [
                'header' => 'inventory create',
                'instructions' => '<p>Inventory create page instructions.</p>
<p>Use this page to record your asset inventory to assist with inventory assignment, collection, and reconciliation.
<br />
For example: A package of 25 new cummerbunds has just been received.  You\'ll number these items and then assign the items to your ensemble members.  Use this form to record item-specific information.
</p>
<div>
<ul>
<li><strong>Asset</strong>: Select the previously created asset category from the drop-down list.
<ul>
<li>Note: Clicking the "Submit and Add Another" button when submitting the form will remember this selection so that you can simply tab through it on subsequent entries.</li>
</ul>
</li>
<li><strong>Item Id</strong>: Enter the item number/id that you use for the item.</li>
<li><strong>Size</strong>: If the item has a size and you want to track it, enter that here.  This field can be left blank if unneeded.
<ul>
<li>Note: This will also be remembered by the system.</li>
</ul>
</li>
<li><strong>Color</strong>: If the item has a color or colors and you want to track it, enter that here.  This field can be left blank if unneeded.
ul>
<li>Note: This will also be remembered by the system.</li>
</ul>
</li>
<li><strong>Status</strong>: The "available" status is pre-selected, but can be changed to: "assigned", "lost", "removed", or "unreturned" if needed.</li>
<li><strong>Comments</strong>Use this field to record any additional comments or information that might be helpful in the future.</li>
</ul>
</div>'
            ],
            [
                'header' => 'inventory edit',
                'instructions' => '<p>Inventory edit page instructions</p>
<p>Inventory edit page instructions...</p>'
            ],
            [
                'header' => 'events dashboard',
                'instructions' => '<p>Events dashboard page instructions</p>
<p>Events dashboard page instructions...</p>'
            ],
            [
                'header' => 'my events',
                'instructions' => '<p>Events Table page</p>
<p>This page displays all the events for which you are the event manager or in which you have been assigned a management role by the event manager.</p>
<p>An event contains information that does not change (for example: name, sponsoring organization, grade eligibility, etc.) and information that may vary each time the event happens.</p>
<p><strong>Click the green button with the white plus sign to add your first event!</strong></p>
<p>The variable information is contained in the event <b>versions</b>.  You can access that information by clicking one of the three buttons under the "Version" column:
<ul>
<li><strong>Current</strong>: This button will display if at least one version has been created.  This is the most recent version of the event.</li>
<li><strong>All</strong>: This button will display if more than one version has been created. This will display a table containing all versions for which you have a management role.</li>
<li><strong>New</strong>: This will open a page to create a new version.
<ul>
<li>Note: This button only displays for the current event manager(s).</li>
</ul>
</li>
</ul>
</p>
<p>Anyone with an assigned event management role (ex. registration manager) will see this table but only persons assigned the event manager role may edit or remove events.</p>',
            ],
            [
                'header' => 'new event',
                'instructions' => '<p>New Event page</p>
<p>The New Event page contains event information that is unlikely to change between event occurrences. Once saved, the page will change to "edit" mode and include a subordinate form(s) to add information about the event ensembles.</p>
<p><strong>Please do not include variable information (ex: years or dates) in any of the fields below.</strong>
<p>
<p><strong><u>General Event Information</u></strong></p>
<ul class="mb-4">
<li><strong>Name</strong>: The formal name of the event.</li>
<li><strong>Short Name</strong>: A shorter, informal name of the event to be used when brevity is required.</li>
<li><strong>Organization Name</strong>: The name of the sponsoring organization.</li>
<li><strong>Logo File</strong>: If you want to use your event or organization logo, upload that image file here.  The system expects a jpg, jpeg, or png file extension.</li>
<li><strong>Eligibile Grades</strong>: The student grades eligible to participate in the event.  Please separate each grade with a comma (ex: 9,10,11,12).</li>
<li><strong>Status</strong>: The current status of the event.
<ul>
<li><strong>Active</strong>: The event has or will have active versions for member participation.</li>
<li><strong>Closed</strong>: The event has been canceled and no longer expects to have active versions for member participation.  A "closed" status will preclude the creation of a new version and will reclassify any currently active versions as "closed".
<br />This status should be used when the event is truly canceled or where significant changes are required (ex. different grade eligibility, different number of ensembles) that support the creation of a new event. </li>
<li><strong>Inactive</strong>: The event has been paused (ex: Covid outbreak) but is expected to resume at some time in the future when the current emergency passes.  An "inactive" status will preclude the creation of a new version and will reclassify any currently active versions as "inactive".</li>
<li><strong>Sandbox</strong>: Use this setting when you start to create a new event.  The "sandbox" setting will allow you to do everything <u>except</u> invite members to participate.</li>
</ul>
</li>
<li><strong>Maximum Number Of Registrants</strong>: If your event limits the maximum number of registrants per school, select that number here.  Use zero if you have no limit on the number of registrants per school.</li>
<li><strong>Maxiumum Number of Upper Voice Registrants</strong>: If your event limits the maximum number of soprano and alto combined registrants per school, select that number here.  Use zero if you have no limit on the number of upper voice registrants per school.</li>
<li><strong>Ensemble count</strong>: Following auditions, successful registrants are assigned to ensembles.  Select the number of ensembles to which your registrants will be assigned. Changing this number will change the number of subordinate ensemble forms displayed to the right of the event form.
<ul>
<li>Note: Once the first version is closed, <strong>reducing</strong> this number should be considered a significant change as it will impact any historical data and it is recommended that you <u>create a new event</u>. Increasing the number of ensembles has no impact on the historical data and is NOT considered a significant change.</li>
</ul>
</li>
<li><strong>Height</strong>: Click this checkbox if you need to know your registrant\'s height.</li>
<li><strong>Shirt Size</strong>: Click this checkbox if you need to know your registrant\'s shirt size.</li>
</ul>',
            ],
            [
                'header' => 'event edit',
                'instructions' => '<p>Event Edit page</p>
<p>The Event Edit page contains the information about the event which is unlikely to change between event occurrences. It also includes a subordinate form(s) to add information about the event ensembles.</p>
<p><strong>Please do not include variable information (ex: years or dates) in any of the fields below.</strong>
<p>
<p>The Event Edit page consists of two sections: General event information and event ensemble information</p>
<p><strong><u>General Event Information</u></strong></p>
<ul class="mb-4">
<li><strong>Name</strong>: The formal name of the event.</li>
<li><strong>Short Name</strong>: A shorter, informal name of the event to be used when brevity is required.</li>
<li><strong>Organization Name</strong>: The name of the sponsoring organization.</li>
<li><strong>Logo File</strong>: If you want to use your event or organization logo, upload that image file here.  The system expects a jpg, jpeg, or png file extension.</li>
<li><strong>Eligibile Grades</strong>: The student grades eligible to participate in the event.  Please separate each grade with a comma (ex: 9,10,11,12).</li>
<li><strong>Status</strong>: The current status of the event.
<ul>
<li><strong>Active</strong>: The event has or will have active versions for member participation.</li>
<li><strong>Closed</strong>: The event has been canceled and no longer expects to have active versions for member participation.  A "closed" status will preclude the creation of a new version and will reclassify any currently active versions as "closed".
<br />This status should be used when the event is truly canceled or where significant changes are required (ex. different grade eligibility, different number of ensembles) that support the creation of a new event. </li>
<li><strong>Inactive</strong>: The event has been paused (ex: Covid outbreak) but is expected to resume at some time in the future when the current emergency passes.  An "inactive" status will preclude the creation of a new version and will reclassify any currently active versions as "inactive".</li>
<li><strong>Sandbox</strong>: Use this setting when you start to create a new event.  The "sandbox" setting will allow you to do everything <u>except</u> invite members to participate.</li>
</ul>
</li>
<li><strong>Maximum Number Of Registrants</strong>: If your event limits the maximum number of registrants per school, select that number here.  Use zero if you have no limit on the number of registrants per school.</li>
<li><strong>Maxiumum Number of Upper Voice Registrants</strong>: If your event limits the maximum number of soprano and alto combined registrants per school, select that number here.  Use zero if you have no limit on the number of upper voice registrants per school.</li>
<li><strong>Ensemble count</strong>: Following auditions, successful registrants are assigned to ensembles.  Select the number of ensembles to which your registrants will be assigned. Changing this number will change the number of subordinate ensemble forms displayed to the right of the event form.
<ul>
<li>Note: Once the first version is closed, <strong>reducing</strong> this number should be considered a significant change as it will impact any historical data and it is recommended that you <u>create a new event</u>. Increasing the number of ensembles has no impact on the historical data and is NOT considered a significant change.</li>
</ul>
</li>
<li><strong>Height</strong>: Click this checkbox if you need to know your registrant\'s height.</li>
<li><strong>Shirt Size</strong>: Click this checkbox if you need to know your registrant\'s shirt size.</li>
</ul>
<p><strong><u>Event Ensemble Information</u></strong></p>
<p>This subordinate form links ensemble information to the event. </p>
<ul>
<li><strong>Name</strong>: The formal name of the ensemble. Ensembles <u>must have</u> unique ensemble names.</li>
<li><strong>Short Name</strong>: A shorter, informal name of the ensemble to be used when brevity is required.</li>
<li><strong>Eligible Grades</strong>: If there is only one ensemble, this is likely to mirror the grades of the event.  If there are multiple ensembles, this will be used to further define which successfully auditioned registrants are eligible to be assigned to this ensemble.</li>
<li><strong>Voice Parts</strong>: Click the checkboxes that identify the voice parts to be used in this ensemble. this will be used to further define which successfully auditioned registrants are eligible to be assigned to this ensemble.</li>
</ul>',
            ],
            [
                'header' => 'version profile',
                'instructions' => '<p>Version Profile page</p>
<p>The Version Profile page collects basic information about the current event\'s new version.  Information from previous versions will be used whenever possible to reduce your workload.</p>
<p>When submitted, the system will display the version dashboard for your use in creating the full version profile.</p>
<p>This form will collect information about the following:
<ul>
                    <li><strong>Name</strong>: The formal name of the event version.  This will likely include information that will vary between event versions, like date or year.</li>
                    <li><strong>Short Name</strong>: The information name of the event version for use whenever space is at a premium.</li>
                    <li><strong>Senior Class</strong>: The graduating senior class at the time of the event.</li>
                    <li><strong>Status</strong>:  Select from the drop-down menu and used to determine if/when the system should be open to your members.</li>
                    <li><strong>Upload files</strong>: This is used to determine if system space will be required for uploading files.</li>
                    <li><strong>Fees:</strong>: This is used to determine which fees must be accounted for within the system.
                    <ul>
<li><strong>Registration</strong>: The fee charged at the beginning of the process for students to register for the event.</li>
                    <li><strong>On-Site Registration</strong>: The fee charged to students registering on the day of the event.  Leave this as zero if students are not permitted to register on-site.</li>
                    <li><strong>Participation</strong>: The fee charged to successfully auditioned students who are assigned to an ensemble and will be participating in the event\'s concert.</li>
                    </ul></li>
                    <li><strong>PayPal</strong>: Click these boxes if you wish to have your teachers or students submit fees via PayPal.  Where students are permitted to pay via PayPal, the teacher will always have the individual discretion to allow/deny their students this option.<ul><li><strong>Teachers</strong>: As above.</li><li><strong>Students</strong>: As above.</li></ul></li>
                    <li><strong>Pitch Files</strong>: Use the checkbox to indicate if you will be storing your pitch files in the system to be made available to your teachers and their students.</li>
                </ul>
</p>'
            ],
            [
                'header' => 'version edit profile',
                'instructions' => '<p>Version Edit Profile page</p>
<p>The Version Edit Profile page collects basic information about the current event\'s new version.  Information from previous versions will be used whenever possible to reduce your workload.</p>
<p>When submitted, the system will display the version dashboard for your use in creating the full version profile.</p>
<p>This form will collect information about the following:
<ul>
                    <li><strong>Name</strong>: The formal name of the event version.  This will likely include information that will vary between event versions, like date or year.</li>
                    <li><strong>Short Name</strong>: The information name of the event version for use whenever space is at a premium.</li>
                    <li><strong>Senior Class</strong>: The graduating senior class at the time of the event.</li>
                    <li><strong>Status</strong>:  Select from the drop-down menu and used to determine if/when the system should be open to your members.</li>
                    <li><strong>Upload files</strong>: This is used to determine if system space will be required for uploading files.</li>
                    <li><strong>Fees:</strong>: This is used to determine which fees must be accounted for within the system.
                    <ul>
<li><strong>Registration</strong>: The fee charged at the beginning of the process for students to register for the event.</li>
                    <li><strong>On-Site Registration</strong>: The fee charged to students registering on the day of the event.  Leave this as zero if students are not permitted to register on-site.</li>
                    <li><strong>Participation</strong>: The fee charged to successfully auditioned students who are assigned to an ensemble and will be participating in the event\'s concert.</li>
                    </ul></li>
                    <li><strong>PayPal</strong>: Click these boxes if you wish to have your teachers or students submit fees via PayPal.  Where students are permitted to pay via PayPal, the teacher will always have the individual discretion to allow/deny their students this option.<ul><li><strong>Teachers</strong>: As above.</li><li><strong>Students</strong>: As above.</li></ul></li>
                    <li><strong>Pitch Files</strong>: Use the checkbox to indicate if you will be storing your pitch files in the system to be made available to your teachers and their students.</li>
                </ul>
</p>'
            ],
            [
                'header' => 'version dashboard',
                'instructions' => '<p>Version dashboard page</p>
<p>Version dashboard page instructions</p>'
            ],
            [
                'header' => 'versions',
                'instructions' => '<p>Versions table page</p>
<p>Versions table page instructions</p>'
            ],
            [
                'header' => 'version configs edit',
                'instructions' => '<p>Version Configurations edit page</p>
<p>Version Configurations edit page instructions</p>'
            ],
            [
                'header' => 'version dates edit',
                'instructions' => '<p>Version Dates edit page</p>
<p>If you are using this for the first time, please note the following:
<ul>
<li>Date values created by the system will use the current date and then set the "open"
values to one second after midnight (12:00:01 AM) and the close values to one second after
midnight (11:59:59 PM).</li>
<li>
If you are creating a new version and a previous version is available, the system will add one
 year to the previous version\'s value to provide a suggested date.
</li>
<li>Dates control access to TheDirectorsRoom.com pages as follows:
<ul>
<li><strong>Administrative</strong>: ONLY members that you have identified with a management
role will have access to Event Management pages between the dates/times indicated.</li>
<li><strong>Membership</strong>: Registration pages will be made available to your membership
between the dates/times indicated.</li>
<li><strong>Final Teacher Changes</strong>: Your membership teachers will be able to edit
their student\'s registration information up to the date/time indicated.  Note that the
this date is typically set a few days after the <strong>Student Close Date</strong>
to give the teachers time to review their student input. <br />It is recommended
that you set this date\'s time to something <u>within normal business hours</u> otherwise you may
find yourself monitoring system activity much later in the night for the procrastinators in
your membership.</li>
<li><strong>Postmark</strong>: This is advisory only and has not impact on the system processes,
but it can be used to advise your membership of the postmark deadline if one exists.</li>
<li><strong>Student</strong>: Your membership teacher\'s should remind their students that
registration information updates is ONLY available from StudentFolder.info between these
dates/times.  Note that the <strong>Final Teacher Changes</strong> are typically set a few
days after this close date/time to give the teachers time to review their student input.</li>
<li><strong>Judging</strong>: The adjudication pages will be available to those members with
assigned judging roles between the dates/times indicated. <br />It is recommended
that you set this date\'s time to something <u>within normal business hours</u> otherwise
you may find yourself monitoring system activity much later in the night for the procrastinators in
your membership.</li>
<li><strong>Tab Room</strong>: The tab room pages will be available to those members with
assigned Tab Room roles between the date/times indicated.  Tab Room pages are used to monitor
adjudication work, to assign cut-off values, run final scoring reports, and release results.</li>
</ul>
</li>
</ul></p>'
            ],
            [
                'header' => 'version participants',
                'instructions' => '<p>Version Participation page</p>
<p>Version participation page displays all participants to the selected version.</p>
<p>Using the "Search" box, you may search by the participant or school\'s  name.</p>
<p>The table may be sorted by name, school or status by clicking the
appropriate column header.</p>
<p>When creating a new version, teachers who have completed the obligations form or who
have participated in the last event version will be automatically added to your roster with
the status of "invited".  The is done to save you the effort of re-loading participants who
are likely to participate again.  You should review this list and remove any teachers who should
not be included.</p>
<p>There are two short forms embedded in the table:
<ul>
<li><strong>Add Participant</strong> form: Accessed by clicking the green button with the plus sign.
<ul>
<li>You can find a participate by entering their <strong>email address</strong>,or</li>
<li>Selecting the <strong>participants name</strong> from the drop-down box.</li>
<li>Once found, an "Invite This Teacher?" button will display to add that teacher to your participants roster.</li>
<li>Participant status will automatically change from "invited" when the participant hits certain milestones (ex. clicking through the "Obligations" page or when students become candidates for auditions).</li>
</ul></li>
<li><strong>Edit Participant</strong> form: Accessed by clicking the indigo "Edit" button. Note that the only data you may edit on a participant is their status.</li>
</ul></p>'
            ],
            [
                'header' => 'version roles',
                'instructions' => '<p>Version Roles page instructions</p>
<p>Use this page to assign event version management roles to version participants.</p>
<p>Note: A teacher <strong>must be a participant</strong> of the version prior to being assigned a role!</p>'
            ],
            [
                'header' => 'version pitch files',
                'instructions' => '<p>Pitch Files page instructions</p>
<p>Use this page to add/edit/remove pitch files to be used by your member teachers and their students.</p>
<p>Click the green button with the plus sign to add a new pitch file.</p>
<p>As new pitch files are added, the filters on the left-hand side of the table will expand to display additional voice parts and file types as these are added.  Use the checkboxes to restrict the display of pitch files.</p>
<p>There are three sortable columns on the table; voice part, file type, and order.  Click the column header to sort the table by these columns.</p>
<p>Clicking the indigo "Edit" button will allow changes to voice part, file type, description, and order.  If you need to change the file, please use the red "Remove" button to remove the row and then the green plus sign button to add a new row with the correct pitch file.</p>
<p>Please note that the "order" column will determine the order in which the pitch files are displayed to your members and their students. <strong>It is recommended</strong> that you
<ul>
<li>carefully think the through file order before you start adding new files, and </li>
<li>visually check the order in the table below as you add new files </li>
</ul>
as changing the order will have a cascading effect on the subsequent files requiring a change to each file that comes after the one changed.</p>
<p>You can use the "Export" button to export your work to a .csv file.</p>
'
            ],
            [
                'header' => 'version scoring',
                'instructions' => '<p>Scoring page instructions</p>
<p>User this page to add/edit/remove the specific scoring segments upon which your judges score.</p>
The fields available are:
<ul class="mb-4">
<li><strong>File Type</strong>: is established on the Version Dashboard->Configuration:Adjudication page under the heading "What File Types Will Be Uploaded?". If you are doing an on-site audition, this could be referred to as scoring categories.  Typically these are high-level groupings that are further defined by specific scoring segments.</li>
<li><strong>Segment</strong>: This identifies a specific quality upon which the judges score.</li>
<li><strong>Abbr</strong>: A <u>very</u> short (1-3 character) abbreviation for the segment.  This is used on the score sheets, so brevity is critical!</li>
<li><strong>Order</strong>: The system uses this number to determine how scores should be presented to the user either on individual pages or on full-detail pdf forms.  The order represents the entire scoring pattern of <u>ALL</u> segments.  For example: if you have ten total scoring segments, then the order should be one through 10.</li>
<li><strong>Best</strong>: The best score that the auditioner can be given for the specific segment.</li>
<li><strong>Worst</strong>: The worst score that the auditioner can be given for the specific segment.</li>
<li><strong>Multiplier</strong>: If your scoring calculation includes a multiplier factor, that should be indicated here.  The system will default to "1" (i.e. no multiplier).</li>
<li><strong>Tolerance</strong>: Tolerance is the allowable difference in scores between the judges. If you have a tolerance specific to the segment, choose that here.  The system will default to "0" (i.e. no tolerance is applied to the specific segment.)</li>
</ul>
<p>Click the indigo "Edit" button to edit any of your previous entries.</p>
<p>Click the red "Remove" button to remove the segment.</p>'
            ],
            [
                'header' => 'events participations',
                'instructions' => '<p>Events Participation instructions page</p>
<p>Use this page to navigate between current and past events, and to request an
invitation to new events.</p>'
            ],
            [
                'header' => 'versions table',
                'instructions' => '<p>Versions table instructions page</p>
<p>Use this page to navigate between the event\'s versions.</p>'
            ],
            [
                'header' => 'participation active',
                'instructions' => '<p>Active Participation instructions page</p>
<p>Use this page to complete the work necessary to have your students participate
in the current version.</p>'
            ],
            [
                'header' => 'participation results',
                'instructions' => '<p>Use this page to review the results of your
student\'s participation in a past event.</p>'
            ],
            [
                'header' => 'candidates',
                'instructions' => '<p>Use this page to edit your students\' information for this event.</p> <p>The candidates roster provides a robust set of options: <ul> <li><strong>Filters</strong>: Located on the left-hand side of the roster, filters allow you to restrict the display to: <ul> <li><strong>Grade</strong>: Limit the display to one-or-more grade levels.</li> <li><strong>Status</strong>: Limit the display to one-or-more of the current status values (ex. eligible, engaged, prohibited, registered, removed, withdrew). The following will provide a general definition of these status values: <ul> <li><strong>Eligible (gray)</strong>: The student is in eligible grade-level.</li> <li><strong>Engaged (yellow)</strong>: The student (or you) have created an action which indicates interest in participation in the current event version.  This will vary based on the event version requirements, but generally the engaged status indicates that an application has been downloaded or signed, or a recording has been uploaded.</li> <li><strong>Prohibited (red)</strong>: The student has been prohibited from engaging in the current event version by the event\'s management.  Once prohibited, the student will not be able to move further in the registration process.  Please contact the event manager if you believe that this status is in error.</li> <li><strong>Registered (green)</strong>: This will vary based on the event version requirements, but generally a registered status indicates that voice part is correct, the application signatures have been teacher-approved, and all recordings have been uploaded and approved.</li> <li><strong>Removed (red)</strong>: The student has been removed from engaging in the current event version by the student\'s teacher (generally you, but it may have been instated by the student\'s previous teacher). Please contact the event manager if you believe that this status is in error.</li> <li><strong>Withdrew (red)</strong>: The student has determined that they will not be participating and has requested that their candidacy be removed from consideration. </li> </ul></li> </ul></li> <li><strong>Candidates</strong>: All grade-eligible students will be displayed on the page along with their
color-coded status.</li> </ul></p>
<p><strong>Registration Information</strong></p><ul><li>Header</strong>: The form header will display with the student\'s status and a color-coded background.  As you update the student\'s information, an update message will display below the status value.</li><li><strong>Click for Path to Registration</strong>: Click this button to display a bulleted-list of registration requirements that have been completed (in green) and those that are incomplete (in red).</li>
<li><strong>Biographic</strong>: Information like name, grade, email, phones are included in this section.
Please note the following:
<ul>
<li><strong>Required fields</strong> are noted with a red asterisk.</li>
<li><strong>Auditioning Voice Parts</strong>: The system maintains a default voice part which can be updated
by you in the "Students" section of TheDirectorsRoom.com.  This value is used whenever possible to determine
the most likely voice part that the student will be auditioning for the current event version.  However, the
auditioning Voice Part is maintained separately from the default voice part so that the student is free to
audition on any valid voice part without needing to change their default voice part.</li>
<li><strong>Program Name</strong>: Student sometimes wish to have the event program display a different name
than that used in the classroom.  This value will default to the student\'s name on the system but can be
changed if needed.  The event management determines if the Program Name will be used on their program.</li>
</ul></li>
<li><strong>Sponsoring Teacher</strong>: If you have a co-teacher, a drop-down box will display to allow you
to reset the student\'s sponsoring teacher.  If you are the only teacher, your name will display.</li>
<li><strong>Emergency Contacts</strong>:Each student must have at least one Emergency Contact including a mobile phone number. If this exists, that information will be displayed.  Otherwise, an advisory (No Emergency Contacts Found!) will be displayed in red.  Emergency Contact information can be updated in the "Students" section of TheDirectorsRoom.com.</li>
<li><strong>Home Address</strong>: If the event requires the student home address and it exists, that information will be displayed.  Otherwise, an advisory (No or partial home address found.) will be displayed in red.  Home Address information can be updated by you in the "Students" section of TheDirectorsRoom.com. If the student home address is not required, this section will not be displayed.</li>
<li><strong>Application</strong>: Use this section to review the signature status of the student application and/or download a copy of the application.<ul><li>If the event uses an <strong>eApplication</strong>, two signature boxes (student and parent/guardian) will be displayed, allowing you to authorize these signatures for the student and/or parent/guardian.</li><li>If the event uses a <strong>physical application</strong>, a single checkbox will display allowing you to verify the student and parent/guardian signatures on the physical application.</li><li>If the auditioning voice part or emergency contact information is missing, an advisory will be displayed detailing the missing requirements.  The application <strong>cannot</strong> be downloaded until this missing information is completed.</li></ul></li>
<li><strong>Audition Recordings</strong>: This section will display if the event uses audition recordings for adjudication.<ul><li>If <strong>no recording</strong> has been uploaded, you will be able to upload the recording from the displayed form.</li><li>If a recording <strong>has been uploaded</strong>, you will be able to Accept or Reject that recording.</strong><li>Once <strong>accepted</strong>, the date/time of acceptance will be displayed along with a button to Reject the file (in case you change your mind).</li><li>When you <strong>Reject</strong> an uploaded file, that file is <u>deleted</u> from the system and the form to upload another file will be displayed.</li></ul></li>
</ul>'
            ],
            [
                'header' => 'obligations',
                'instructions' => '<p>Use this page to acknowledge your
obligations to this event.</p>'
            ],
            [
                'header' => 'pitchfiles',
                'instructions' => '<p>Use this page to play pitch files and display related pdfs.</p>
<p>Click on either the "voice part" or "type" column headers to sort on that column.
<p>Use the filters on the left-hand side of the table to restrict the display of pitch files and pdfs.</p>
<p>Filter and sorting selections are saved and will be re-used when you next open the page.</p>'
            ],
            [
                'header' => 'estimate',
                'instructions' => '<p>Use this page to play review the data for your estimate
form or invoice, pay via PayPal (if allowed by the event), and update the records for
your student payments.<br />
Note that these student payment records are intended only for your internal use and
are not used by the event management.
</p>'
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
