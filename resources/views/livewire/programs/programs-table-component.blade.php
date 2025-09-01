<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH --}}
    @if($hasSearch)
        <div class="px-4 w-11/12 ">
            <div class="flex flex-col">
                <div class="flex flex-row w-full space-x-2 ">
                    <input wire:model="search"
                           class="w-3/4"
                           type="text"
                           placeholder="Search by program title, school year, tag, or song title"
                           aria-label="Search"
                    />
                    <button
                        wire:click="updateSearchCriteria"
                        type="button"
                        class="bg-black text-white px-2 rounded-lg"
                    >
                        Search
                    </button>
                </div>
                <div id="hint" class="text-xs italic">
                    Place song title between double-quotes (ex: "Battle Hymn of the Republic")
                </div>

                @if($titleSearchResults)
                    <div id="titleSearchResults">
                        {!! $titleSearchResults !!}
                    </div>
                @endif
            </div>


        </div>
    @endif

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <button
                type="button"
                wire:click="addNew"
                class="bg-green-500 text-white text-3xl px-2 rounded-lg"
                title="Add New"
                tabindex="-1"
            >
                +
            </button>
        </div>


        {{-- FILTERS AND TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters)
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="['schools']" header="{{ $filters->header }}" :hcEvents="$hcEvents"/>
                </div>
            @endif

            {{-- FORMS AND TABLE--}}
            <div class="flex flex-col">

                {{-- TABLE --}}
                <div>
                    @if($hcEvents)
                        @include('components.tables.hcEventsProgramsTable')
                    @else
                        @include('components.tables.programsTable')
                    @endif
                </div>

            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>{{-- END OF PAGE CONTENT --}}

</div>






