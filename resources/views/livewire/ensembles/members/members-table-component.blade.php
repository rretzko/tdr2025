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
            <x-buttons.addNew route="schoolEnsembleMember.create"/>
        </div>

        {{-- FILTERS AND TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters)
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="['schools', 'ensembles']"/>
                </div>
            @endif

            {{-- TABS AND TABLE--}}
            <div class="flex flex-col w-full">

                {{-- TABS --}}
                <x-tabs.genericTabs :selectedTab="$selectedTab" :tabs="$tabs"/>

                {{-- TABLE --}}
                <table class="px-4 shadow-lg w-full">
                    <caption>n={{ count($rows) }}</caption>
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

                    @forelse($rows AS $row)

                        <tr
                            @class([
                                'odd:bg-green-50',
                                'text-gray-400, bg-gray-50, odd:bg-gray-50' => (! ($row->status === 'active')),
                            ])
                        >
                            <td class="border border-gray-200 px-1">
                                <div>{{ $row->name }}</div>
                                student name
                                <div class="ml-2 text-xs italic">{{ $row->schoolName }}</div>
                            </td>
                            <td class="border border-gray-200 px-1 text-center">
                                {{ $row->ensembleName }}
                            </td>
                            <td class="border border-gray-200 px-1 text-center">
                                {{ $row->voicePartDescr }}
                            </td>
                            <td class="border border-gray-200 px-1 text-center">
                                {{ $row->class_of }}
                            </td>
                            <td class="border border-gray-200 px-1 text-center">
                                {{ $row->school_year }}
                            </td>
                            <td class="border border-gray-200 px-1 text-center">
                                {{ $row->status }}
                            </td>
                            <td class="border border-gray-200 px-1 text-center">
                                {{ $row->office }}
                            </td>
                            <td class="text-center border border-gray-200">
                                <x-buttons.edit id="{{ $row->id }}" route="schoolEnsembleMember.edit"/>
                            </td>
                            <td class="text-center border border-gray-200">
                                <x-buttons.remove id="{{ $row->id }}" livewire="1"/>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                                No {{ $dto['header'] }} found.
                            </td>
                        </tr>
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





