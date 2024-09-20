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

        <div @class([
            "",
            "bg-red-50 border border-red-800 px-2 my-2 rounded-lg text-red-800 " => $gradesAreMissing,
        ])
        >
            @if($gradesAreMissing)
                WARNING: The <b>add-student</b> and <b>edit-student</b> buttons have been temporarily disabled because
                we were unable to find grades for you.
                Please click
                <a href="{{ route('school.edit',['school' => $school]) }}" class="text-red-800 underline">here</a>
                or use the Schools link on the left, and then the Edit button on your school to update this information.
            @endif
        </div>

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <div class="flex items-center space-x-2">
                <x-buttons.addNew route="student.create" :disabled="$gradesAreMissing"/>
                <x-buttons.export/>
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
                <x-tables.studentsTable
                    :columnHeaders="$columnHeaders"
                    :disabled="$gradesAreMissing"
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

