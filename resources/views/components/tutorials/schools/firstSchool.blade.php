<div id="firstSchool" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">Setting Up Your First School</h3>
    <div class="ml-2 flex flex-col">
        <p>The linkage of teacher-to-school is fundamental to everything you do on TheDirectorsRoom.com.
            For this reason, the first action following your registration to TheDirectorsRoom.com is to
            complete the Schools form. </p>

        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Empty School Form</label>
                <div id="emptySchoolFormImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/schools/schoolsFormEmpty.png') }}"
                         alt="Empty School form">
                </div>
            </div>
        </div>

        <p class="my-2">
            The empty school form has eight fields:
        </p>

        <ul class="ml-8 mt-2 list-disc">
            <li>
                <span class="text-yellow-200">Zip Code</span>: Enter the zip code of the school.
                <ul class="ml-8 list-disc text-sm">
                    <li>
                        "Zip Code" is a search field. After tabbing out of the field, the system will
                        automatically display the names of the schools matching that zip code.
                    </li>
                    <li>
                        <img src="{{ Storage::disk('s3')->url('tutorials/schools/postalCodeSearch.png') }}"
                             alt="Zip code search results">
                    </li>
                    <li>
                        Clicking a link from the search results will complete the zip code, name, abbreviation,
                        city, county, and grades-taught-in-school fields.
                    </li>
                </ul>
            </li>
            <li>
                <span class="text-yellow-200">School Name</span>: Enter the full school name
                <u>without abbreviations.</u>
                <ul class="ml-8 list-disc text-sm">
                    <li>
                        "School Name" is a search field. After entering at least five characters and
                        tabbing out of the field, the system will automatically display the names of the
                        schools matching that school name fragment.
                    </li>
                    <li>
                        <img src="{{ Storage::disk('s3')->url('tutorials/schools/schoolNameSearch.png') }}"
                             alt="School name search results">
                    </li>
                    <li>
                        Clicking a link from the search results will complete the zip code, name, abbreviation,
                        city, county, and grades-taught-in-school fields.
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
        </ul>
    </div>
</div>
