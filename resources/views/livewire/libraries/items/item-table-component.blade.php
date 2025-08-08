<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH --}}
    @if($hasSearch)
        <div class="flex px-4 w-11/12 justify-between items-center">
            <input
                wire:model.live.debounce="globalSearch"
                class="w-3/4"
                type="text"
                placeholder="Search title, artist name, or tag"
            />

            <div>
                <button wire:click="export" class="text-blue-500" title="export library items">
                    <x-heroicons.arrowDownTray/>
                </button>
            </div>
        </div>

    @endif

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="flex justify-between mb-1">
            <div class="flex flex-row space-x-1 items-center mb-2">
                <div>{{ $library->name }} Items /</div>
                <select wire:model.live="voicingFilterId">
                    <option value="0">ALL</option>
                    @forelse($voicings AS $key => $value)
                        <option value="{{ $key }}">
                            {{ strtoupper($value) }}
                        </option>
                    @empty
                        {{-- do nothing else --}}
                    @endforelse
                </select>
                <div> /</div>
                <select wire:model.live="typeFilterId">
                    @foreach($typeFilters AS $key => $typeFilter)
                        <option value="{{ $key }}">
                            {{ strtoupper($typeFilter) }}
                        </option>
                    @endforeach
                </select>
                {{-- item count --}}
                <div class="text-xs italic">
                    ( {{ count($rows) }} items found)
                </div>
            </div>
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

            {{-- TABLE--}}
            <div>
                @include('components.tables.itemsTable')
            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>{{-- END OF PAGE CONTENT --}}

</div>






