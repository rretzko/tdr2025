<div id="firstEnsemble" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">Setting Up Your First Ensemble</h3>
    <div class="ml-2 flex flex-col">
        <p>Clicking on the "Ensembles" card will display the Ensembles application with an empty table.</p>
        <p class="my-2">Click the green Plus-sign button to open the Ensemble form.</p>
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Ensembles card on Home page</label>
                <div id="ensemblesCardImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/ensemblesCardFromHomePage.png') }}"
                         alt="Ensembles card from home page">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Empty Ensembles Table</label>
                <div id="emptyEnsemblesTableImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/emptyEnsemblesTable.png') }}"
                         alt="Empty Ensembles table">
                </div>
            </div>
        </div>

        <p class="my-2">
            The ensemble form has six fields:
        </p>

        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>New Ensemble Form</label>
                <div id="newEnsembleFormImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/newEnsembleForm.png') }}"
                         alt="New Ensemble Form">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Completed Ensemble Form</label>
                <div id="completedEnsembleFormImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/completedEnsembleForm.png') }}"
                         alt="Completed Ensemble Form">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Ensembles Table With Ensemble</label>
                <div id="ensembleTableWithEnsembleImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/ensemblesTableWithEnsemble.png') }}"
                         alt="Ensemble table with ensemble">
                </div>
            </div>
        </div>

        <ul class="ml-8 mt-2 list-disc">
            <li>
                <span class="text-yellow-200">Name</span>: Enter the name of the ensemble.
            </li>
            <li>
                <span class="text-yellow-200">Short Name</span>: Enter the short name of the ensemble.
                This is useful when space is cramped.
            </li>
            <li>
                <span class="text-yellow-200">Abbreviation</span>: Enter the abbreviation of the ensemble.
                This is used on tables and forms to conserve space.
            </li>
            <li>
                <span class="text-yellow-200">Description</span>: This box is small but can contains a
                very lengthy description of your ensemble.
            </li>
            <li>
                <span class="text-yellow-200">Grades</span>: This series of checkboxes is derived from
                the "Grades You Teach" in the Schools application.
            </li>
            <li>
                <span class="text-yellow-200">Active</span>: Use this checkbox to indicate if the new
                ensemble is active or needed for historical reference.
            </li>
        </ul>
        <p class="my-2">
            Note the "<span class="text-yellow-200">members</span>" column on the ensembles table returns a ratio (0/0).
            These numbers will
            represent the total number of ensemble members throughout the years and the current school year's
            membership numbers.
        </p>
    </div>
</div>
