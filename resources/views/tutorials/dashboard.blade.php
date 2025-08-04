<x-layouts.tutorial>
    <x-slot name="header">Tutorial Dashboard</x-slot>

    <p class="underline mb-2">
        Use the left-hand menu select a specific tutorial.
    </p>

    <p>
        <a href="https://thedirectorsroom.com">TheDirectorsRoom.com</a> is composed of seven modules:
    </p>
    <style>
        .moduleName {
            font-weight: bold;
        }
    </style>
    <ul class="ml-8 list-disc mb-2">

        {{-- SCHOOLS --}}
        <li>
            <span class="moduleName">Schools</span>
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
            <span class="moduleName">Students</span>
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
            <span class="moduleName">Ensembles</span>
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
        <li><span class="moduleName">Libraries</span></li>
        <li><span class="moduleName">Programs</span></li>
        <li><span class="moduleName">Events</span></li>
        <li><span class="moduleName">Profile</span></li>
    </ul>


</x-layouts.tutorial>
