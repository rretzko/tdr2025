<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <div class="flex items-center space-x-2">
                <x-buttons.export/>
            </div>
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row ">

            {{--             TABLE WITH LINKS--}}
            <div class="flex flex-col space-y-2 mb-2 w-full">

                @include('components.tables.participationFeesTable')

                {{--                <x-links.linkTop :recordsPerPage="$recordsPerPage" :rows="$rows"/>--}}

                {{--                --}}{{--                 TABLE--}}
                {{--                <x-tables.teacherObligationsTable--}}
                {{--                    :columnHeaders="$columnHeaders"--}}
                {{--                    :header="$dto['header']"--}}
                {{--                    :membershipCardRequired="$membershipCardRequired"--}}
                {{--                    :recordsPerPage="$recordsPerPage"--}}
                {{--                    :rows="$rows"--}}
                {{--                    :sortAsc="$sortAsc"--}}
                {{--                    :sortColLabel="$sortColLabel"--}}
                {{--                />--}}

                {{--                 LINKS:BOTTOM--}}
                {{--                <x-links.linkBottom :rows="$rows"/>--}}

            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>

</div>
