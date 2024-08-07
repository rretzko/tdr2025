<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }} </div>
        </div>

        {{-- TABS --}}
        <x-tabs.estimateTabs :tabs="$tabs" :selected-tab="$selectedTab"/>


        {{-- FILTERS and TABLE --}}
        <div class="flex flex-col">

            @if($selectedTab === 'estimate')
                <div class="w-11/12 mx-auto">
                    <x-tables.estimateTableForRegistrants
                        :columnHeaders="$columnHeaders"
                        :header="$dto['header']"
                        registrationFee="{{  $registrationFee }}"
                        :rows="$registrants"
                        sortAsc="{{  $sortAsc }}"
                        sortColLabel="{{  $sortColLabel }}"
                        versionId="{{  $versionId }}"
                    />
                </div>
            @endif

            @if($selectedTab === 'payments')
                <div>
                    Payments Table
                </div>
            @endif

            @if($selectedTab === 'payPal')
                <div>
                    PayPal Form
                </div>
            @endif

            {{-- FILTERS --}}
            {{--            @if($hasFilters && count($filterMethods))--}}
            {{--                <div class="flex justify-center">--}}
            {{--                    <x-sidebars.filters :filters="$filters" :methods="$filterMethods"/>--}}
            {{--                </div>--}}
            {{--            @endif--}}

            {{--            <div class="flex flex-col w-full">--}}
            {{--                <x-tables.participationPitchFilesTable--}}
            {{--                    :rows="$rows"--}}
            {{--                    :columnHeaders="$columnHeaders"--}}
            {{--                    header="$header"--}}
            {{--                    sortAsc="$sortAsc"--}}
            {{--                    sortColLabel="$sortColLabel"--}}
            {{--                />--}}

        </div>
    </div>
</div>

</div>
