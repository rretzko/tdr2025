<div id="addMembers" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">Adding Members To A Program's Ensemble</h3>
    <div class="ml-2 flex flex-col">
        <p>Click the Ensemble Title (Concert Choir in this example.)
            <img src="{{ Storage::disk('s3')->url('tutorials/programs/ensembleLinkForMembers.png') }}"
                 alt="Ensemble link to open members form"
                 class="inline mx-2"/>
            to display the members side bar.</p>

        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Empty Members Side Bar</label>
                <div id="membersSidebarEmpty">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/emptyMembersSidebar.png') }}"
                         alt="Empty members sidebar">
                </div>
            </div>
        </div>

        <p class="my-2">
            There are three ways to add ensemble members:
        </p>
        <ol class="ml-8 list-decimal">
            <li>
                Via the
                <a href="/tutorial/ensembles#ensembleMembers" class="text-blue-500">Ensembles application</a>.
            </li>
            <li>
                Adding individual members via the side bar link.
                <ul class="ml-8 list-disc">
                    <li>
                        <img src="{{ Storage::disk('s3')->url('tutorials/programs/sidebarLinkIndividualMembers.png') }}"
                             alt="Sidebar link for adding individual student members"/>
                    </li>
                </ul>
            </li>
            <li>
                Using a csv file to perform a mass add action.
                <ul class="ml-8 list-disc">
                    <li>
                        <img src="{{ Storage::disk('s3')->url('tutorials/programs/sidebarLinkMassAdd.png') }}"
                             alt="Sidebar link for adding students via csv file"/>
                    </li>
                </ul>
            </li>
        </ol>


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
