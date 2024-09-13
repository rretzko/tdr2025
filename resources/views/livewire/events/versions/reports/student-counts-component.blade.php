<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH and RECORDS PER PAGE --}}
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
                {{--                <x-buttons.addNew route="student.create"/>--}}
                <x-buttons.export/>
            </div>
        </div>

        {{-- VOICE PART SUMMARY TABLE --}}
        <div id="summaryTable" class="w-full bg-gray-100 px-2 py-1 my-2 rounded-lg">
            @include('components.forms.partials.voicePartSummaryTable')
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row ">

            {{-- FILTERS--}}
            @if($hasFilters && count($filterMethods))
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="$filterMethods" :values="$participatingSchools"/>
                </div>
            @endif

            {{-- PAYMENT FORM AND TABLE WITH LINKS--}}
            <div class="flex flex-col space-y-2 mb-2 w-full">

{{--                <x-links.linkTop :recordsPerPage="$recordsPerPage" :rows="$rows"/>--}}

                {{-- TABLE--}}
                <x-tables.studentCountsTable
                    :columnHeaders="$columnHeaders"
                    :header="$dto['header']"
                    :recordsPerPage="$recordsPerPage"
                    :rows="$rows"
                    :sortAsc="$sortAsc"
                    :sortColLabel="$sortColLabel"
                />

                {{-- LINKS:BOTTOM--}}
                {{--                <x-links.linkBottom :rows="$rows"/>--}}

            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>

</div>





