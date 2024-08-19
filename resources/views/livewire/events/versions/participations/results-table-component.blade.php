<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ $version->short_name . ' ' . ucwords($dto['header']) }} ({{ $rows->total() }})</div>
        </div>

        {{-- SEARCH and RECORDS PER PAGE --}}
        <div class="flex flex-row justify-between px-4 w-full">

            @if($hasSearch || ($rows->total() > 15))

                {{-- SEARCH --}}
                @if($hasSearch)
                    <x-tables.searchComponent/>
                @else
                    {{-- empty div for spacing --}}
                    <div></div>
                @endif

                {{-- RECORDS PER PAGE --}}
                @if($rows->total() > 15)
                    <x-forms.indicators.recordsPerPage/>
                @endif

            @endif

        </div>

        {{-- PAGE CONTENT --}}
        <div class="w-11/12">

            {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
            <div class="flex justify-between mb-1">
                <div>{{ ucwords($dto['header']) }}</div>
                {{--                <div class="flex items-center space-x-2">--}}
                {{--                    <x-buttons.addNew route="student.create"/>--}}
                {{--                    <x-buttons.export/>--}}
                {{--                </div>--}}
                <div wire:click="printResultsAll()"
                     class="flex flex-row items-center my-2 text-blue-600 text-sm cursor-pointer"
                >
                    Print All Results
                    <span class="ml-2"><x-heroicons.printer/></span>
                </div>
            </div>

            {{-- FILTERS and TABLE --}}
            <div class="flex flex-row ">

                {{-- FILTERS --}}
                @if($hasFilters && count($filterMethods))
                    <div class="flex justify-center">
                        <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>
                    </div>
                @endif

                {{-- TABLE WITH LINKS --}}
                <div class="flex flex-col space-y-2 mb-2 w-full">

                    <x-links.linkTop :recordsPerPage="$recordsPerPage" :rows="$rows"/>

                    {{-- TABLE --}}
                    <x-tables.auditionResultsTable
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

</div>



