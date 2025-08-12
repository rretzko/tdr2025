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
        <li><span id="events" class="text-yellow-100 font-semibold">Events</span></li>
        <li><span id="profile" class="text-yellow-100 font-semibold">Profile</span></li>
    </ul>


</x-layouts.tutorial>
