<div id="manageEventsApplication" class="mb-4 pt-2 border border-transparent border-t-gray-200">

    <h3>
        <span class="font-semibold underline">Manage Events</span>
        <span class="ml-2 text-sm hover:underline hover:text-yellow-200">
            <a href="{{ route('tutorial.events.manageEventsDetail') }}">
                (click here for Manage Events detail page)
            </a>
        </span>
    </h3>

    <div class="mb-2">
        <p class="mb-2">
            The Manage Events is a <a href="/#pricing">for-fee</a>
            application used by TheDirectorsRoom.com teachers who are
            also responsible for event management and administration, typically with an outside
            organization like ACDA or NAfME.
        </p>
        <p class="mb-2">
            Auditions for Honors Choirs are complex events with the details of each event varying
            narrowly year-to-year and more widely between different events and organizations.
        </p>

        <p class="mb-2">
            For that reason, the Manage Events application is comprised of role-based sections with multiple
            configuration sub-sections within each section:
        </p>

        <ul class="ml-8 list-disc">
            <li>
                <span lass="text-yellow-200">Event Manager</span>: The primary decision-maker for an event,
                The Event Manager has access to ALL aspects and sub-applications with an event.
                <ul class="ml-8 list-disc text-sm">
                    <li>Version Profile</li>
                    <li>Configurations</li>
                    <li>Dates</li>
                    <li>Participants</li>
                    <li>Event Version Roles</li>
                    <li>Pitch Files</li>
                    <li>Scoring</li>
                    <li>Attachments</li>
                </ul>
            </li>
            <li>
                <span lass="text-yellow-200">Online Registration Manager</span>: manages the day-to-day
                process and procedure questions which may arise from teachers regarding the online
                registration process.
                <ul class="ml-8 list-disc text-sm">
                    <li>Student Transfer</li>
                </ul>
            </li>
            <li>
                <span lass="text-yellow-200">Registration Manager</span>: manages the process that moves the
                event from the close of registration, through the audition process, and up to the audition date.
                <ul class="ml-8 list-disc text-sm">
                    <li>Co-registration Managers</li>
                    <li>Judge Assignment</li>
                    <li>School Timeslots</li>
                    <li>Registration Reports</li>
                </ul>
            </li>
            <li>
                <span lass="text-yellow-200">Tab Room</span>: members of the tab room oversee the audition
                process through the assignment of final cut-off scores.
                <ul class="ml-8 list-disc text-sm">
                    <li>Add/Edit Scores</li>
                    <li>Adjudication Tracking</li>
                    <li>Ensemble Cut-offs</li>
                    <li>Tabroom Reports</li>
                    <li>Tabroom Close Auditions</li>
                </ul>
            </li>
            <li>
                <span lass="text-yellow-200">Rehearsal Manager</span>: manages the post-audition process of
                rehearsals up to the final concert.
                <ul class="ml-8 list-disc text-sm">
                    <li>Participation Fees</li>
                </ul>
            </li>
        </ul>
    </div>

    <div id="pricing" class="border border-transparent border-t-gray-200 pt-2">

        <h3 class="font-semibold underline mb-2">Pricing</h3>

        <style>
            #pricingTbl {
                color: black;
            }

            #pricingTbl td, th {
                border: 1px solid darkgray;
                padding: 0 0.25rem;
                text-align: center;
            }
        </style>
        <table id="pricingTbl" class="bg-gray-100 text-gray-800 border border-gray-500">
            <thead>
            <tr>
                <th>Application Type</th>
                <th>Per Registrant</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>On Site</td>
                <td>$1.25</td>
            </tr>
            <tr>
                <td>Remote</td>
                <td>$2.75</td>
            </tr>
            <tr>
                <td>Recording Storage</td>
                <td>$24.99/month stored</td>
            </tr>
            </tbody>
        </table>
    </div>

</div>
