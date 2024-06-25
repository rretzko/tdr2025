<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- SEARCH and RECORDSPERPAGE --}}
    <div class="flex flex-row justify-between px-4 w-full">

        {{-- SEARCH --}}
        <div class="flex flex-row justify-start border border-gray-600 pl-1">
            <div class="flex justify-center items-center ">
                <x-heroicons.magnifyingGlass class=""/>
            </div>
            <input wire:model.live.debounce="search" class="border border-transparent focus:border-transparent"
                   type="text" placeholder="Search"/>
        </div>

        {{-- RECORDS PER PAGE --}}
        <x-forms.indicators.recordsPerPage/>

    </div>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
            <x-buttons.addNew route="student.create"/>
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row">

            {{-- FILTERS --}}
            @if($hasFilters)
                <div class="flex justify-center">
                    <x-sidebars.filters :filters="$filters" :methods="['schools', 'classOfs', 'voicePartIds']"/>
                </div>
            @endif

            {{-- TABLE WITH LINKS --}}
            <div class="flex flex-col space-y-2 mb-2">
                <div>Count: {{ count($rows) }}</div>
                {{-- LINKS:TOP --}}
                <div class="shadow-lg">
                    {{ $rows->links() }}
                </div>

                {{-- TABLE --}}
                <div class="relative">
                    <table class="px-4 shadow-lg w-full">
                        <thead>
                        <tr>
                            @foreach($columnHeaders AS $columnHeader)
                                <th class='border border-gray-200 px-1'>
                                    <button
                                        @if($columnHeader['sortBy']) wire:click="sortBy('{{ $columnHeader['sortBy'] }}')" @endif
                                        @class([
                                        'flex items-center justify-center w-full gap-2 ',
                                        'text-blue-500' => ($columnHeader['sortBy'])
                                        ])
                                    >
                                        <div>{{ $columnHeader['label'] }}</div>
                                        @if($sortColLabel === $columnHeader['sortBy'])
                                            @if($sortAsc)
                                                <x-heroicons.arrowLongUp/>
                                            @else
                                                <x-heroicons.arrowLongDown/>
                                            @endif
                                        @endif
                                    </button>
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
                            <tr class=" odd:bg-green-50 ">
                                <td class="text-center">
                                    {{ $loop->iteration + (($rows->currentPage() - 1) * $recordsPerPage) }}
                                </td>
                                <td class="border border-gray-200 px-1">
                                    <div>{{ $row['last_name'] . ($row['suffix_name'] ? ' ' . $row['suffix_name'] : '') . ', ' . $row['first_name'] . $row['middle_name'] }}</div> {{-- student name --}}
                                    <div class="ml-2 text-xs italic">{{ $row['schoolName'] }}</div>
                                    <div class="ml-2 text-xs italic">{{ $row['email'] }}</div>
                                    <div class="ml-2 text-xs italic">{{ $row['phoneMobile'] }}</div>
                                    <div class="ml-2 text-xs italic">{{ $row['phoneHome'] }}</div>
                                </td>
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['classOf'] }} {{-- ex. 2026 (11th grade) --}}
                                </td>
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['voicePart'] }} {{-- ex. baritone --}}
                                </td>
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['height'] }} {{-- ex. 64 (5' 4") --}}
                                </td>
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['birthday'] }} {{-- ex. Jan 1, 2010 --}}
                                </td>
                                <td class="border border-gray-200 px-1 text-center">
                                    {{ $row['shirtSize'] }}
                                </td>
                                <td class="text-center border border-gray-200">
                                    <x-buttons.edit id="{{ $row['studentId'] }}" route="student.edit"/>
                                </td>
                                <td class="text-center border border-gray-200">
                                    <x-buttons.remove id="{{ $row['studentId'] }}" livewire="1"/>
                                </td>
                            </tr>

                        @empty
                            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                                No {{ $dto['header'] }} found.
                            </td>
                        @endforelse
                        </tbody>
                    </table>

                    <div wire:loading class="absolute inset-0 bg-white opacity-50">
                        {{--  --}}
                    </div>

                    <div wire:loading.flex class="flex justify-center items-center absolute inset-0">
                        <x-heroicons.spinner size="6" class="text-gray-500"/>

                    </div>
                </div>

                {{-- LINKS:BOTTOM --}}
                <div>
                    {{ $rows->links() }}
                </div>
            </div>

        </div>

        {{-- SUCCESS INDICATOR --}}
        <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                             message="{{  $successMessage }}"/>
    </div>

</div>

