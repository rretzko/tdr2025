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
            <div class="flex flex-col">
                <label>Completed Members Side Bar</label>
                <div id="membersSidebarCompleted">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/completedMembersSidebar.png') }}"
                         alt="Completed members sidebar">
                </div>
            </div>
        </div>

        <p class="my-2">
            To add new members to an ensemble for a specific school year, click the "Ensembles" link.
            This will open the "new members" page in the
            <a href="/tutorial/ensembles#ensembleMembers" class="text-blue-500">Ensembles</a>
            application.
        </p>

    </div>
</div>
