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

            {{-- TABS AND TABLE--}}
            <div class="flex flex-col">

                {{-- TABS --}}
                <x-tabs.genericTabs :selectedTab="$selectedTab" :tabs="$tabs"/>

                {{-- TABLE --}}
                <table class="px-4 shadow-lg w-full">
                    <thead>
                    <tr>
                        @foreach($columnHeaders AS $columnHeader)
                            <th
                                class="border border-gray-200 px-1 @if($columnHeader === 'active?') text-blue-500 @endif"
                                title="@if($columnHeader === 'active?') Click to change... @endif"
                            >
                                {{ $columnHeader }}
                            </th>
                        @endforeach
                        <th class="border border-transparent px-1 sr-only">
                            edit
                        </th>
                        <th class="border border-gray-200 px-1 sr-only">
                            remove
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($rows AS $ensembles)

                        @forelse($ensembles AS $row)

                            <tr
                                @class([
                                    'odd:bg-green-50',
                                    'text-gray-400, bg-gray-50, odd:bg-gray-50' => (! $row['active']),
                                ])
                            >
                                {{-- ENSEMBLE NAME AND SCHOOL --}}
                                <td class="border border-gray-200 px-1">
                                    <div>{{ $row['name'] }}</div> {{-- ensemble name --}}
                                    <div class="ml-2 text-xs italic">{{ $row['schoolName'] }}</div>
                                </td>

                                {{-- SHORT NAME --}}
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['short_name'] }}
                                </td>

                                {{-- ABBREVIATION --}}
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['abbr'] }}
                                </td>

                                {{-- DESCRIPTION --}}
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['description'] }}
                                </td>

                                {{-- GRADES --}}
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['grades'] }}
                                </td>

                                {{-- MEMBER COUNTS --}}
                                <td class="border border-gray-200 px-1 text-center cursor-help text-indigo-500">
                                    <span title="current active">{{ $memberCounts[$row['id']]['countCurrent'] }}</span>
                                    /
                                    <span
                                        title="lifetime any status">{{ $memberCounts[$row['id']]['countLifetime'] }}</span>
                                </td>
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['active'] ? 'Y' : 'N' }}
                                </td>

                                {{-- ASSETS COLUMN --}}
                                {{--
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ array_key_exists($row['id'], $ensembleAssetsArray) ? implode(', ', $ensembleAssetsArray[$row['id']]) : '' }}
                                </td>
                                --}}

                                {{-- EDIT BUTTON --}}
                                <td @class([
                                    "text-center px-2",
                                    "border border-gray-200" => $row['canEdit'],
                                    ])
                                >
                                    @if($row['canEdit'])
                                        <x-buttons.edit id="{{ $row['id'] }}" route="ensemble.edit"/>
                                    @endif
                                </td>

                                {{-- REMOVE BUTTON --}}
                                <td @class([
                                    "text-center px-2",
                                    "border border-gray-200" => $row['canRemove'],
                                    ])
                                >
                                    @if($row['canRemove'] && (! $memberCounts[$row['id']]['countLifetime'] ))
                                        <x-buttons.remove id="{{ $row['id'] }}" livewire="1"/>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            {{--                    do nothing--}}
                        @endforelse

                    @empty
                        <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                            No {{ $dto['header'] }} found.
                        </td>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>{{-- END OF PAGE CONTENT --}}

</div>




