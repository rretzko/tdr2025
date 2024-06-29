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

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <div class="flex items-center space-x-2">
                <x-buttons.addNew route="schoolEnsembleMember.create"/>
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

                {{-- TABLE --}}
                <x-tables.schoolEnsemblesMembersTable
                    :columnHeaders="$columnHeaders"
                    :header="$dto['header']"
                    :rows="$rows"
                    sortColLabel="{{  $sortColLabel }}"
                    :sortAsc="$sortAsc"
                />

            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>{{-- END OF PAGE CONTENT --}}

</div>





