<div id="firstStudent" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">Setting Up Your First Student</h3>
    <div class="ml-2 flex flex-col">
        <p>
            Clicking on the "Students" card will display the Students application with an empty table
            as shown below.
        </p>

        {{-- IMAGES --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Students card on Home page</label>
                <div id="studentsCardImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/students/studentsCardFromHomePage.png') }}"
                         alt="Ensembles card from home page">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Empty Students Table</label>
                <div id="studentsTableEmpty">
                    <img src="{{ Storage::disk('s3')->url('tutorials/students/emptyStudentsTable.png') }}"
                         alt="Empty students table">
                </div>
            </div>
        </div>

        <p class="mt-4 mb-2">Click the green Plus-sign button from the Students table to open the Student form.</p>

        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Empty Student Form</label>
                <div id="emptyStudentFormImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/students/emptyStudentForm.png') }}"
                         alt="Empty Student form">
                </div>
            </div>
        </div>

        <p class="my-4">
            When saved, the "edit" version of the student form is displayed with four tabs:
            <img src="{{ Storage::disk('s3')->url('tutorials/students/studentFormTabs.png') }}" alt="Student form tabs"
                 class="inline"/>
        </p>

        <ul class="ml-8 list-disc">
            <li>
                <span class="text-yellow-200">Bio</span>: containing all of the fields from the "empty
                student form" shown above <i>except</i> the email and phones which are on the "comms" form.
            </li>
            <li>
                <span class="text-yellow-200">Comms</span>: Email, phones, and home address.
            </li>
            <li>
                <span class="text-yellow-200">Emergency Contact</span>: Name, relationship, email, phones.
                <ul class="ml-8 list-disc text-sm">
                    <li>As many emergency contacts as needed may be added.</li>
                </ul>
            </li>
            <li>
                <span class="text-yellow-200">Reset Password</span>: A single button used to reset the
                student's password to the lower-case version of their email address.
            </li>
        </ul>

        <p class="my-4">
            The completed version of each tab is displayed below.
        </p>

        {{-- IMAGES --}}
        <div
            class="mt-4 p-2 flex flex-col space-y-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Completed Student Bio</label>
                <div id="studentsBio">
                    <img src="{{ Storage::disk('s3')->url('tutorials/students/completedStudentBio.png') }}"
                         alt="Completed student bio form">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Completed Student Comms</label>
                <div id="studentsComms">
                    <img src="{{ Storage::disk('s3')->url('tutorials/students/completedStudentComms.png') }}"
                         alt="Completed student comms form">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Completed Student Emergency Contact</label>
                <div id="studentsEmergencyContact">
                    <img src="{{ Storage::disk('s3')->url('tutorials/students/completedStudentEmergencyContact.png') }}"
                         alt="Completed student emergency contact form">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Completed Student Reset-Password</label>
                <div id="studentsResetPassword">
                    <img src="{{ Storage::disk('s3')->url('tutorials/students/completedStudentResetPassword.png') }}"
                         alt="Completed student reset-password form">
                </div>
            </div>
        </div>
    </div>
</div>
