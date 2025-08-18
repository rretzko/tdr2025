<div id="schoolTable" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">School Table</h3>
    <div class="ml-2 flex flex-col">
        <p>After the first school has been created, the School Table will display</p>

        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>School Table</label>
                <div id="schoolTableImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/schools/schoolsTableOneSchool.png') }}"
                         alt="School table with one school">
                </div>
            </div>
        </div>

        <p class="my-2">
            While most of the columns are self-explanatory, please note the following:
        </p>

        <ul class="ml-8 mt-2 list-disc">
            <li>
                <span class="text-yellow-200">Active</span>: Displays as a
                green checkmark <img src="{{ Storage::disk('s3')->url('tutorials/schools/greenCheckmark.png') }}"
                                     alt="Green checkmark badge" class="mx-2 inline"/>
                or a red thumbs-down <img src="{{ Storage::disk('s3')->url('tutorials/schools/redThumbsDown.png') }}"
                                          alt="Red thumbs-down badge" class="mx-2 inline"/>
                badge.
                <ul class="ml-8 list-disc text-sm">
                    <li>
                        This defaults to the green checkmark badge when the school is created and then updated
                        as required when the verification email is sent
                        and again when the verification email is returned.
                    </li>
                </ul>
            </li>
            <li>
                <span class="text-yellow-200">Verified</span>: Displays as a
                green checkmark <img src="{{ Storage::disk('s3')->url('tutorials/schools/greenCheckmark.png') }}"
                                     alt="Green checkmark badge" class="mx-2 inline"/>
                or a red thumbs-down <img src="{{ Storage::disk('s3')->url('tutorials/schools/redThumbsDown.png') }}"
                                          alt="Red thumbs-down badge" class="mx-2 inline"/>
                badge.
                <ul class="ml-8 list-disc text-sm">
                    <li>
                        This defaults to the red thumbs-down badge until the verification email is returned.
                    </li>
                </ul>
            </li>
            <li>
                <span class="text-yellow-200">Abbreviation</span>: Enter the abbreviation of the school name.
                This is used on tables and forms to conserve space.
            </li>
            <li>
                <span class="text-yellow-200">City</span>: Enter the name of the city in which the school resides.
            </li>
            <li>
                <span class="text-yellow-200">County</span>: Select the county name from the drop-down box.
                <ul class="ml-8 list-disc text-sm">
                    <li>
                        This value is critical to some audition events managed through TheDirectorsRoom.com, and
                        therefore is a required field.
                    </li>
                    <li>
                        If the county of your school is not listed, please use the "Unknown" value.
                    </li>
                </ul>
            </li>
            <li>
                <span class="text-yellow-200">Grades Taught in School</span>: Click the appropriate
                checkboxes to indicate the grades are taught in the school.
                These values are used to help determine eligibility, ranking, and other segmentation concerns.
            </li>
            <li>
                <span class="text-yellow-200">Grades I Teach in School</span>: Click the appropriate
                checkboxes to indicate the grades that <u>you</u> teach in the school.
                These values are used to help determine eligibility, ranking, and other segmentation concerns.
            </li>
            <li>
                <span class="text-yellow-200">Work Email</span>: Enter your work email even if it is a
                duplicate of the email you are using to log into TheDirectorsRoom.com.
                <ul class="ml-8 list-disc text-sm">
                    <li>
                        To help maintain the integrity of the system and especially access to student data
                        maintained in the system, periodic verification emails are sent to ensure that teachers
                        remain in the schools noted as "active". For this reason, the "work email" field will
                        reject email addresses using commercial domains like: hotmail.com, gmail.com, apple.com,
                        etc.
                    </li>
                </ul>
            </li>
            <li>
                <span class="text-yellow-200">Subjects</span>: This will default to "chorus" and you may
                add "band" and/or "orchestra" from the form that displays when you click the "Edit" button.
            </li>
            <li>
                <span class="text-yellow-200">Edit</span>: Click the indigo "Edit"
                <img src="{{ Storage::disk('s3')->url('tutorials/schools/buttonEdit.png') }}" alt="Indigo edit button"
                     class="mx-2 inline"/>
                button add/update any of the editable fields from the form.
            </li>
            <li>
                <span class="text-yellow-200">Deactivate</span>: Click the grey
                <img src="{{ Storage::disk('s3')->url('tutorials/schools/buttonDeactivate.png') }}"
                     alt="Grey deactivate button" class="mx-2 inline"/>
                "Deactivate" button to switch
                a school's "active?" status from the green checkMark to the red thumbs-down.
                <ul class="ml-8 list-disc text-sm">
                    <li>
                        Note: You must have at least one active school at all times. For this reason, the
                        "Deactivate" button may display as a simple dash on one row to ensure that at least
                        one school is guaranteed to be "active".
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</div>
