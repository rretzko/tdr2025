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

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <x-buttons.addNew route="ensemble.create"/>
        </div>

        {{-- FILTERS AND TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters)
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="['schools']"/>
                </div>
            @endif

            {{-- TABS, TABLE, and FORM--}}
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">

                {{-- TABS and TABLE --}}
                <div class="flex flex-col">

                    {{-- TABS --}}
                    <x-tabs.genericTabs :$selectedTab :$tabs/>

                    {{-- TABLE --}}
                    <x-tables.ensembleAssetsTable :$columnHeaders :$rows/>

                </div>

                {{-- FORM --}}
                <div class="min-w-64 p-4">
                    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">
                        <h3>Add/Edit Your Assets</h3>

                        <x-forms.elements.livewire.labeledInfoOnly
                            label="SysId"
                            wireModel="sysId"
                        />

                        <x-forms.elements.livewire.inputTextNarrow
                            label="asset name"
                            name="assetName"
                            required="true"
                        />

                        <x-buttons.submit/>

                    </form>
                </div>
            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>{{-- END OF PAGE CONTENT --}}

</div>
