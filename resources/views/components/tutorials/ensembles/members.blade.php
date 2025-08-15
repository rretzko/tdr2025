<div id="ensembleMembers" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">Adding Members To An Ensemble</h3>
    <div class="ml-2 flex flex-col">
        <p>Clicking "Members" from the Ensembles tabs
            <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/ensemblesTabs.png') }}" alt="Ensemble Tabs"
                 class="inline mx-2"/>
            will display an empty members table.</p>
        <p class="my-2">Click the green Plus-sign button to open the Members form.</p>
        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Empty Members Table</label>
                <div id="membersTableEmpty">
                    <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/membersTableEmpty.png') }}"
                         alt="Empty members table">
                </div>
            </div>
        </div>

        <p class="my-2">
            The members form displays some information and allows for two types of data entry:
        </p>
        <ul class="ml-8 list-disc mb-2">
            <li>Information: Provides reference information for you to confirm that you're entering member
                information for the right school and the right ensemble.
                <ul class="ml-8 list-disc text-sm">
                    <li>
                        <span class="font-semibold">Sys.Id</span>: will display as "new" for a new entry, or
                        a number for an existing entry. This is for reference information only.
                    </li>
                    <li>
                        <span class="font-semibold">School</span>: will display the current school's name. This
                        is only relevant if you have multiple schools in your Schools application. In this case,
                        the field will display as a drop-down box for you to select alternate schools.
                    </li>
                    <li>
                        <span class="font-semibold">Ensemble</span>: will display the current ensemble's name.
                        This is only relevant if you have multiple ensembles.
                        In this case, the field will display as a drop-down box for you to select alternate
                        ensembles.
                    </li>
                </ul>
            </li>
            <li>Data Entry Types
                <ul class="ml-8 list-disc text-sm">
                    <li>Individual Student, and</li>
                    <li>Mass Add.</li>
                </ul>
            </li>
        </ul>

        {{-- INDIVIDUAL STUDENT DATA ENTRY --}}
        <div class="font-semibold">
            Individual Student
        </div>
        <div>
            To add an individual student,
            <ol class="ml-8 list-decimal">
                <li>Enter a school year
                    <ul class="ml-8 list-disc text-sm">
                        School years are identified by the last year, ex: the school year 2025-26 is identified
                        by 2026.
                    </ul>
                </li>
                <li>
                    Enter a non-member student's first or last name.
                    <ul class="ml-8 list-disc text-sm">
                        <li>
                            "Non-member" means a student who has not been identified as a member to the
                            current ensemble in the selected school year.
                        </li>
                        <li>
                            To save your typing, the system will automatically search for non-member students
                            based on the school year identified above and the text input in Non-member Name field.
                        </li>
                        <li>
                            These students will be displayed beneath the field for your selection (click).
                        </li>
                        <li>
                            <img
                                src="{{ Storage::disk('s3')->url('tutorials/ensembles/nonMemberNameSelectionExample.png') }}"
                                alt="Non-member name selection example"/>
                        </li>
                        <li>Click the student's name to display the remaining form fields:</li>
                        <li>
                            <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/nonMemberFormAllFields.png') }}"
                                 alt="Non-member form with all fields"/>
                        </li>
                        <li>
                            Note that the system will automatically complete the "Non-member Name" and "Voice Part"
                            fields
                            using information stored for that student from the "Students" application.
                        </li>
                        <li>
                            Note also that the "Office" field will default to "member" and "Status" field will default
                            to "active". You can, of course, change these by clicking the respective drop-down box and
                            selecting a different option.
                        </li>
                        <li>
                            Clicking the "Save" button will save your data entry and then return you to the Members
                            page.
                        </li>
                        <li>
                            <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/membersPageOneRow.png') }}"
                                 alt="Members page with one member included"/>
                        </li>
                        <li>
                            The ensembles page will reflect the change in membership numbers:
                        </li>
                        <li>
                            <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/ensemblesPageOneMember.png') }}"
                                 alt="Ensembles page with one member included"/>
                        </li>
                        <li>
                            Clicking the "Save and Add Another" will save your data entry, clear the fields, and allow
                            you
                            to continue to add students from the form.
                        </li>
                    </ul>
                </li>
            </ol>
        </div>

        {{-- MASS ADD DATA ENTRY --}}
        <div class="font-semibold">
            Mass Add
        </div>
        <ol class="ml-8 list-decimal">
            <li>
                The mass-add method may be a simpler route to add students to an ensemble, although visibly
                challenging if you have many non-member students. "
                <ul class="ml-8 list-disc">
                    <li>
                        <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/massAddForm.png') }}"
                             alt="Mass-add form"/>
                    </li>
                </ul>
            </li>
        </ol>
        <p class="my-2">
            The mass-add form has two fields and then as many checkboxes as there are non-member students who
            are eligible for the selected ensemble in the selected senior year (a proxy for school-year):
        </p>
        <ul class="ml-8 mt-2 list-disc">
            <li>
                <span class="text-yellow-200">Ensemble</span>: Select the name of the ensemble.
            </li>
            <li>
                <span class="text-yellow-200">Senior Year</span>: Select the appropriate senior year.
            </li>
            <li>
                <span class="text-yellow-200">Checkboxes</span>: Click the check box of any student who
                is a member of the selected ensemble in the selected senior year.
            </li>
        </ul>
        <p class="my-2">
            When the "Save New Members" button is clicked, the selected students are saved as members of the
            ensemble and stays on the Member Mass Add. This is done to assist in cases where there are many
            non-member students and selecting students in small groups makes the effort a bit more
            manageable.
        </p>
    </div>
</div>
