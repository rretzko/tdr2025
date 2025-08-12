<x-layouts.tutorial>
    <x-slot name="header">Tutorial Dashboard</x-slot>

    {{-- TABS --}}
    <div class="sticky top-4 z-50 bg-gray-800">
        <div id="tabs"
             class="flex flex-row space-x-1 justify-around sm:justify-start text-sm my-2 "
        >
            <a href="#schools"
               class="flex flex-row space-x-1 border border-yellow-50 text-yellow-100 px-2 py-1 rounded-md inline-flex items-center">
                <x-heroicons.building/>
                <div class="hidden lg:block">Schools</div>
            </a>

            <a href="#students"
               class="flex flex-row space-x-1 border border-yellow-50 text-yellow-100 px-2 py-1 rounded-md inline-flex items-center">
                <x-heroicons.mortarBoard/>
                <div class="hidden lg:block">Students</div>
            </a>

            <a href="#ensembles"
               class="flex flex-row space-x-1 border border-yellow-50 text-yellow-100 px-2 py-1 rounded-md inline-flex items-center">
                <x-heroicons.people/>
                <div class="hidden lg:block">Ensembles</div>
            </a>

            <a href="#libraries"
               class="flex flex-row space-x-1 border border-yellow-50 text-yellow-100 px-2 py-1 rounded-md inline-flex items-center">
                <x-heroicons.bookOpen/>
                <div class="hidden lg:block">Libraries</div>
            </a>
            <a href="#programs"
               class="flex flex-row space-x-1 border border-yellow-50 text-yellow-100 px-2 py-1 rounded-md inline-flex items-center">
                <x-heroicons.ticket/>
                <div class="hidden lg:block">Programs</div>
            </a>
            <a href="#events"
               class="flex flex-row space-x-1 border border-yellow-50 text-yellow-100 px-2 py-1 rounded-md inline-flex items-center">
                <x-heroicons.calendar/>
                <div class="hidden lg:block">Events</div>
            </a>
            <a href="#profile"
               class="flex flex-row space-x-1 border border-yellow-50 text-yellow-100 px-2 py-1 rounded-md inline-flex items-center">
                <x-heroicons.person/>
                <div class="hidden lg:block">Profile</div>
            </a>
        </div>
    </div>

    <p class="underline mb-2">
        Use the left-hand menu select a specific tutorial.
    </p>

    <p>
        <a href="https://thedirectorsroom.com">TheDirectorsRoom.com</a> is composed of seven applications:
    </p>

    {{-- CONTENT --}}
    <style>
        .moduleName {
            font-weight: bold;
        }
    </style>
    <ul class="ml-8 list-disc mb-2">

        {{-- SCHOOLS --}}
        <li>
            <span id="schools" class="text-yellow-100 font-semibold">Schools</span>
            <ul class="ml-4">
                <li>
                    Add, edit, and maintain information about your school or schools including:
                    <ul class="ml-8 list-disc text-sm">
                        <li>Name and location of school,</li>
                        <li>Grades and subjects taught in school,</li>
                        <li>Work email,</li>
                        <li>Optional supervisor emergency contact information, and</li>
                        <li>Assignment/removal of co-teachers at school.</li>
                    </ul>
                </li>
            </ul>
        </li>

        {{-- STUDENTS --}}
        <li>
            <span class="text-yellow-100 font-semibold">Students</span>
            <ul class="ml-4">
                <li>
                    Add, edit, and maintain student information including:
                    <ul class="ml-8 list-disc text-sm">
                        <li>Name, preferred pronoun and school,</li>
                        <li>Grade, default voice part, height, birthday, and shirt size,</li>
                        <li>Email, phone(s), and home address,</li>
                        <li>Emergency contact(s) name, phone(s), and email, and</li>
                        <li>Password reset function for resetting the StudentFolder.info password.</li>
                    </ul>
                </li>
            </ul>
        </li>

        {{-- ENSEMBLES --}}
        <li>
            <span class="text-yellow-100 font-semibold">Ensembles</span>
            <ul class="ml-4">
                <li>Add, edit, and maintain school ensemble information including:
                    <ul class="ml-8 list-disc text-sm">
                        <li>Name, short name, abbreviation, description, grades, and status,</li>
                        <li>Student membership by ensemble and school year,</li>
                        <li>Inventory information of assets (gloves, folders, etc.) to be assigned to students,</li>
                        <li>Asset information of inventory assigned to students</li>
                        <li>Library information of repertoire performed by ensemble and school year.</li>
                    </ul>
                </li>
            </ul>
        </li>

        {{--LIBRARIES --}}
        <li>
            <span id="libraries" class="text-yellow-100 font-semibold">Libraries</span>
            <ul class="ml-4">
                <li>Add, edit, and maintain library items:
                    <ul class="ml-8 list-disc text-sm">
                        <li>Paper copies: Octavos and medleys, text and music books.</li>
                        <li>Digital: web links and pdfs/docs/etc.</li>
                        <li>Recordings: cds, dvds, cassettes, and vinyl.</li>
                    </ul>
                </li>
                <li>Included library item details:
                    <ul class="ml-8 list-disc text-sm">
                        <li>Title and voicing</li>
                        <li>Available copies and price</li>
                        <li>Artists: composer, arrange, words-and-music, words, music, and choreographer</li>
                        <li>Tags: ANY word or phrase that you might find helpful to organize or search for
                            a particular song or types of song
                            <ul>
                                <li>Seasons, key words, language, style, category, etc.</li>
                            </ul>
                        </li>
                        <li>Comments and ratings
                            <ul class="ml-8 list-disc text-sm">
                                <li>Comments: Anything you might want to remind the future-you regarding
                                    the song.
                                </li>
                                <li>Rating: 1-5</li>
                            </ul>
                        </li>
                        <li>Location
                            <ul class="ml-8 list-disc text-sm">
                                <li>The system will automatically provide you with an index number that <i>could</i>
                                    set the foundation of your filing system, but you likely already have
                                    a filing system.
                                </li>
                                <li>For this reason, there are also three open location fields that you
                                    can use to document your personal system.
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        {{-- PROGRAMS --}}
        <li>
            <span id="programs" class="text-yellow-100 font-semibold">Programs</span>
            <ul class="ml-4">
                <li>Add, edit, and maintain an online version of your concert programs
                    <ul class="ml-8 list-disc text-sm">
                        <li>Start with the basics:
                            <ul class="ml-8 list-disc text-sm">
                                <li>School, school year, program title and subtitle, performance date</li>
                                <li>Tags: ANY word or phrase that you might find helpful to organize or search for
                                    a particular song or types of song
                                    <ul>
                                        <li>Seasons, key words, language, style, category, etc.</li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>and then add performance sections based on ensembles or acts
                            <ul class="ml-8 list-disc text-sm">
                                <li>
                                    Choose music from your library to list under each ensemble or act.
                                </li>
                            </ul>
                        </li>
                        <li>Lastly, for ensembles,
                            <ul class="ml-8 list-disc text-sm">
                                <li>Click the ensemble name to add the student names performing in that
                                    ensemble for that school year.
                                </li>
                                <li>
                                    Student names can be added individually or uploaded via a csv file.
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        {{-- EVENTS --}}
        <li><span id="events" class="text-yellow-100 font-semibold">Events</span>
            <ul class="ml-4">
                <li>Events has two sub-applications:
                    <ul class="ml-8 list-disc text-sm">
                        <li>
                            <b>Event Participation</b>: For teachers who are participating in events managed through
                            TheDirectorsRoom.com, and
                            <ul class="ml-8 list-disc text-sm">
                                <li>Event Participation allows you to edit and update event registration information for
                                    students interested in a sponsored event.
                                </li>
                                <li>Candidate Roster: Update the status of students eligible for participation in a
                                    selected event.
                                </li>
                                <li>Candidate information includes status (eligible, engaged, registered, etc.),</li>
                                <li>Student bio information: name, grade, auditioning voice part, email, phone(s),
                                    program name, home address,
                                </li>
                                <li>Emergency contact information: name, relationship, phone(s), email,</li>
                                <li>Sign-offs: student, parent, and teacher,</li>
                                <li>Registration submissions: audio or video recordings</li>
                                <li>When the event closes, this section converts to display the student audition
                                    results.
                                </li>
                            </ul>
                        </li>
                        <li>
                            <b>Manage Events</b>: For the management of events (ex. regional/All-State/other choral
                            events).
                            <ul class="ml-8 list-disc text-sm">
                                <li>Manage Events has multiple sub-applications depending on event management role
                                    <ul class="ml-8 list-disc text-sm">
                                        <li><b>Event Manager</b>
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
                                        <li><b>Online Registration Manager</b>
                                            <ul class="ml-8 list-disc text-sm">
                                                <li>Student Transfer</li>
                                            </ul>
                                        </li>
                                        <li><b>Registration Manager(s)</b>
                                            <ul class="ml-8 list-disc text-sm">
                                                <li>Co-Registration Managers</li>
                                                <li>Judge Assignment</li>
                                                <li>School Timeslots</li>
                                                <li>Registration Reports</li>
                                            </ul>
                                        </li>
                                        <li><b>Tab Room</b>
                                            <ul class="ml-8 list-disc text-sm">
                                                <li>Add/Edit Scores</li>
                                                <li>Adjudication Tracking</li>
                                                <li>Ensemble Cut-offs</li>
                                                <li>Tabroom Reports</li>
                                                <li>Tabroom Close Auditions</li>
                                            </ul>
                                        </li>
                                        <li><b>Rehearsal Manager</b>
                                            <ul class="ml-8 list-disc text-sm">
                                                <li>Participation Fees</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>Please see the Events tutorial for a detailed breakdown of the sub-applications.
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        {{-- PROFILE --}}
        <li><span id="profile" class="text-yellow-100 font-semibold">Profile</span></li>
    </ul>


</x-layouts.tutorial>
