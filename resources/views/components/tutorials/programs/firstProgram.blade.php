<div id="firstProgram" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">Setting Up Your First Program</h3>
    <div class="ml-2 flex flex-col">
        <p>Clicking on the "Programs" card will display the Programs application with an empty table.</p>
        <p class="my-2">Click the green Plus-sign button to open the Program form.</p>
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Programs card on Home page</label>
                <div id="programsCardImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/programsCardFromHomePage.png') }}"
                         alt="Programs card from home page">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Empty Programs Table</label>
                <div id="emptyProgramsTableImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/emptyProgramsTable.png') }}"
                         alt="Empty Programs table">
                </div>
            </div>
        </div>

        <p class="my-2">
            The program form has six fields:
        </p>

        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>New Program Form</label>
                <div id="newProgramFormImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/newProgramForm.png') }}"
                         alt="New Program Form">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Completed Program Form</label>
                <div id="completedProgramFormImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/completedProgramForm.png') }}"
                         alt="Completed Program Form">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Programs Table With Program</label>
                <div id="programsTableWithProgramImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/programsTableWithProgram.png') }}"
                         alt="Progam table with program">
                </div>
            </div>
        </div>

        <ul class="ml-8 mt-2 list-disc">
            <li>
                <span class="text-yellow-200">School</span>: Select the respective school name from
                drop-down box (if multiple schools).
            </li>
            <li>
                <span class="text-yellow-200">School Year</span>: Select the respective school year from
                drop-down box (if multiple schools).
            </li>
            <li>
                <span class="text-yellow-200">Program Title</span>: Enter the program title.
            </li>
            <li>
                <span class="text-yellow-200">Subtitle</span>: Enter the program subtitle.
            </li>
            <li>
                <span class="text-yellow-200">Performance Date</span>: Select the date of the performance.
            </li>
            <li>
                <span class="text-yellow-200">Tags</span>:
                <x-tutorials.tagsBlock/>
            </li>
        </ul>

    </div>
</div>
