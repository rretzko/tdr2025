<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }} ({{ count($rows) }})</div>
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters && count($filterMethods))
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>
                </div>
            @endif

            <div class="flex flex-col w-full">
                <x-tables.participationPitchFilesTable
                    :rows="$rows"
                    :columnHeaders="$columnHeaders"
                    header="$header"
                    sortAsc="$sortAsc"
                    sortColLabel="$sortColLabel"
                />

            </div>
        </div>
    </div>

</div>

