<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH and RECORDSPERPAGE --}}
    <div class="flex flex-row justify-between px-4 w-full">

        {{-- SEARCH --}}
        <div class="flex flex-row justify-start border border-gray-600 pl-1">
            <div class="flex justify-center items-center ">
                <x-heroicons.magnifyingGlass class=""/>
            </div>
            <input wire:model.live.debounce="search" class="border border-transparent focus:border-transparent"
                   type="text" placeholder="Search"/>
        </div>

        {{-- RECORDS PER PAGE --}}
        <x-forms.indicators.recordsPerPage/>

    </div>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <div class="flex items-center space-x-2">
                <x-buttons.addNew route="student.create"/>
                <div class="flex space-x-1 bg-gray-100 border border-gray-600 rounded-lg px-2 text-xs">
                    <x-heroicons.arrowDownTray/>
                    <button wire:click="export" class="cursor-pointer">
                        Export
                    </button>
                </div>
            </div>
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row ">

            {{-- FILTERS --}}
            @if($hasFilters)
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>
                </div>
            @endif

            {{-- TABLE WITH LINKS --}}
            <div class="flex flex-col space-y-2 mb-2 w-full">

                <x-links.linkTop :recordsPerPage="$recordsPerPage" :rows="$rows"/>

                {{-- TABLE --}}
                <x-tables.studentsTable
                    :columnHeaders="$columnHeaders"
                    :header="$dto['header']"
                    :recordsPerPage="$recordsPerPage"
                    :rows="$rows"
                    :sortAsc="$sortAsc"
                    :sortColLabel="$sortColLabel"
                />

                {{-- LINKS:BOTTOM --}}
                <x-links.linkBottom :rows="$rows"/>

            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>

</div>

