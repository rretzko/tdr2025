<div id="ensembleLibrary" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">Ensemble Library</h3>
    <div class="ml-2 flex flex-col">
        <p>The Ensemble library provides a filtered view into your selected library displaying ONLY
            songs chosen from the Programs application specific to the selected Ensemble.
        </p>

        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Ensemble Library Example 1</label>
                <div id="ensembleLibraryExample">
                    <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/ensembleLibraryExample.png') }}"
                         alt="Ensemble Library example one">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Ensemble Library Example 2</label>
                <div id="ensembleLibraryExample">
                    <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/ensembleLibraryExample-2.png') }}"
                         alt="Ensemble Library example two">
                </div>
            </div>

        </div>

        <p class="mt-4">
            You may choose between your saved Ensembles by clicking the appropriate button at the top
            of the page using the Ensemble's abbreviation as it's label.
        </p>
        <div
            class="mt-0 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Ensemble Buttons</label>
                <div id="ensembleButtons">
                    <img src="{{ Storage::disk('s3')->url('tutorials/ensembles/ensembleLibraryButtons.png') }}"
                         alt="Ensemble Library buttons">
                </div>
            </div>
        </div>

        <p class="mt-4">
            Once selected, the page will update to display the respective library items.
        </p>

        <p class="mt-2">
            The "pull" checkboxes can be used to create a pull sheet for specific songs as needed.
        </p>
    </div>
</div>
