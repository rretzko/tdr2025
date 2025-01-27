<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH AND RECORDS PER PAGE --}}
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
                <x-buttons.addNew route="inventory.create"/>
                <x-buttons.export/>
            </div>
        </div>

        {{-- FILTERS AND TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters && count($filterMethods))
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>
                </div>
            @endif

            {{-- TABS AND TABLE--}}
            <div class="flex flex-col w-full">

                {{-- TABS --}}
                <x-tabs.genericTabs :selectedTab="$selectedTab" :tabs="$tabs"/>

                {{-- ENSEMBLE SELECTOR --}}
                <x-forms.elements.livewire.selectWide
                    autofocus=true
                    label="Assets for ensemble: "
                    name="form.ensembleId"
                    :options="$ensembles"
                    required=true
                />

                {{-- TABLE --}}
                <x-tables.InventoriesTable
                    :columnHeaders="$columnHeaders"
                    :header="$dto['header']"
                    :rows="$rows"
                    sortColLabel="{{  $sortColLabel }}"
                    :sortAsc="$sortAsc"
                />

            </div>{{-- END OF TABLS AND TABLE}}

        </div>{{-- END OF FILTERS AND TABLE --}}

        </div>


    </div>
