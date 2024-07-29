<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH and RECORDS PER PAGE --}}
    <div class="flex flex-row justify-between px-4 w-full">

        {{--  SEARCH AND RECORDS PER ROW--}}
        @if($hasSearch || (count($rows) > 15))
            @if($hasSearch)
                <x-tables.searchComponent placeholder="Search name & school"/>
            @else
                <div></div>
            @endif

            {{-- RECORDS PER PAGE --}}
            @if(count($rows) > 15)
                <x-forms.indicators.recordsPerPage/>
            @else
                <div></div>
            @endif
        @endif

    </div>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters && count($filterMethods))
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>
                </div>
            @endif

            {{-- SCROLLABLE LIST OF LINKS --}}
            <div class="flex w-full">
                <!-- Left side: Scrollable list of links -->
                <div class="w-1/2 sm:w-1/4 overflow-y-auto h-screen">
                    <ul class="list-none ml-1">
                        @for($i=0; $i<4; $i++)
                            @foreach($rows as $row)
                                <li>
                                    <button wire:click="selectCandidate({{ $row->candidateId }})"
                                            class="text-xs text-left block py-1 border-b hover:text-blue-500">
                                        {{ $row->last_name . ($row->suffix_name ? ' ' . $row->suffix_name : '') . ', ' . $row->first_name . $row->middle_name }}
                                    </button>
                                </li>
                            @endforeach
                        @endfor
                    </ul>
                </div>

                <!-- Right side: form -->
                <div class="w-full ml-2 overflow-y-auto h-screen">
                    <div class="advisory text-center text-gray-500">
                        @if($form->firstName)
                            <x-forms.partials.candidateForm
                                :auditionFiles="$auditionFiles"
                                :ensembleVoiceParts="$ensembleVoiceParts"
                                :eventGrades="$eventGrades"
                                :heights="$heights"
                                :form="$form"
                                :shirtSizes="$shirtSizes"
                                :statuses="$statuses"
                            />
                        @else
                            Click name to complete form...
                        @endif
                    </div>
                </div>

                {{-- TABLE WITH LINKS --}}
                {{--            <div class="flex flex-col space-y-2 mb-2 w-full">--}}

                {{--                <x-links.linkTop :recordsPerPage="$recordsPerPage" :rows="$rows"/>--}}

                {{--                --}}{{-- TABLE --}}
                {{--                <x-tables.candidatesTable--}}
                {{--                    :columnHeaders="$columnHeaders"--}}
                {{--                    :header="$dto['header']"--}}
                {{--                    :recordsPerPage="$recordsPerPage"--}}
                {{--                    :rows="$rows"--}}
                {{--                    seniorYear="{{  $seniorYear }}"--}}
                {{--                    :sortAsc="$sortAsc"--}}
                {{--                    :sortColLabel="$sortColLabel"--}}
                {{--                />--}}

                {{--                --}}{{-- LINKS:BOTTOM --}}
                {{--                <x-links.linkBottom :rows="$rows"/>--}}

            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>

</div>


