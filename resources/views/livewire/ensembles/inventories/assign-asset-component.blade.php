<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH AND RECORDS PER PAGE --}}
    <div class="flex flex-row justify-between px-4 w-full">

        {{-- SEARCH --}}
        <x-tables.searchComponent/>

        {{-- RECORDS PER PAGE --}}
        <x-forms.indicators.recordsPerPage/>

    </div>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <div class="flex items-center space-x-2">
                <x-buttons.addNew route="schoolEnsembleMember.create"/>
                <x-buttons.export/>
            </div>
        </div>

        {{-- FILTERS AND TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters && count($filterMethods))
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>
                </div>
            @endif

            {{-- SELECTORS AND TABLE--}}
            <div class="flex flex-col w-full">

                {{-- SELECTORS --}}
                <div id="selectors" class="mb-2">

                    {{-- ENSEMBLE --}}
                    <div>
                        <x-forms.elements.livewire.selectNarrow
                            :autofocus="true"
                            label="Ensemble"
                            name="ensembleId"
                            :options="$ensembles"
                            :required="true"
                        />
                    </div>

                    {{-- SCHOOL YEAR --}}
                    <div>
                        <x-forms.elements.livewire.selectCompressed
                            label="School Year"
                            name="srYear"
                            :options="$classOfs"
                            :required="true"
                        />
                    </div>

                </div>

                {{-- TABLE --}}
                <x-tables.assignAssetsTable
                    :columnHeaders="$columnHeaders"
                    :ensembleAssets="$ensembleAssets"
                    :header="$dto['header']"
                    :rows="$rows"
                    sortColLabel="{{  $sortColLabel }}"
                    :sortAsc="$sortAsc"
                />

            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>{{-- END OF PAGE CONTENT --}}

</div>






