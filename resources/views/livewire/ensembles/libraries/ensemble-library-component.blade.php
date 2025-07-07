<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH --}}
    @if($hasSearch)
        <div class="px-4 w-11/12">
            <input class="w-3/4" type="text" placeholder="Search"/>
        </div>
    @endif

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- ENSEMBLE SELECTORS --}}
        <div id="ensembleSelectors"
             class="flex flex-row flex-wrap mb-2 py-1 pl-2 justify-left space-x-2 border border-white border-t-gray-300 border-b-gray-300">
            @forelse($ensembles AS $ensemble)
                <div>
                    <button wire:click="$set('ensembleId', {{  $ensemble['id'] }})"
                            @class([
                                "min-w-[4rem] rounded-lg px-2",
                                'bg-blue-300 text-blue-800 shadow-lg' => ($ensemble['id'] == $ensembleId),
                                'bg-gray-100 text-gray-400' => ($ensemble['id'] != $ensembleId),
                            ])
                            title="{{ $ensemble['name'] }}"
                    >
                        {{ $ensemble['abbr'] }}
                    </button>
                </div>
            @empty
                <div>No ensembles found.</div>
            @endforelse
        </div>

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            @if(! $displayForm)
                <button
                    type="button"
                    wire:click="clickForm"
                    class="bg-green-500 text-white text-3xl px-2 rounded-lg"
                    title="Add New"
                    tabindex="-1"
                >
                    +
                </button>
            @endif
        </div>

        {{-- FILTERS AND TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters)
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="['schools']"/>
                </div>
            @endif

            {{-- FORMS AND TABLE--}}
            <div class="flex flex-col">

                {{-- FORMS --}}
                @if($displayForm)
                    @include('components.forms.ensembles.libraries.ensembleLibraryForm')
                @endif

                {{-- TABLE --}}
                <div>
                    {{--                    @include('components.tables.librariesTable')--}}
                    @include('components.tables.itemsTable')
                </div>

            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>{{-- END OF PAGE CONTENT --}}

</div>
