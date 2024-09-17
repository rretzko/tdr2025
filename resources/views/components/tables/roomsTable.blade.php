@props([
    'columnHeaders',
    'header',
    'rows',
    'sortAsc',
    'sortColLabel',
    'roomJudges',
    'roomScoreCategories',
    'roomVoiceParts',
    'showSuccessIndicator' => false,
    'successMessage' => '',
])
<div class="relative">

    {{-- SUCCESS INDICATOR --}}
    @if($showSuccessIndicator)
        <div class="text-green-600 italic text-xs">
            {{ $successMessage }}
        </div>
    @endif

    <table class="px-4 mt-4 shadow-lg w-full">
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

            <th class="sr-only">
                Edit
            </th>
            <th class="sr-only">
                Remove
            </th>

        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $key => $row)
            <tr class=" odd:bg-green-50 ">

                {{-- COUNTER --}}
                <td class="text-center">
                    {{ ($key + 1) }}
                </td>

                {{-- ROOM NAME --}}
                <td
                    @class(
                        [
                            "border border-gray-200 px-1",
                        ])
                >
                    {{ $row->room_name }}
                </td>

                {{-- VOICE PARTS --}}
                <td
                    @class([
                          "border border-gray-200 px-1",
                    ])
                >
                    {{ isset($roomVoiceParts[$row->id]) ? $roomVoiceParts[$row->id] : 'none found'}}
                </td>

                {{-- CONTENT --}}
                <td class="border border-gray-200 px-1 ">
                    {{ isset($roomScoreCategories[$row->id]) ? $roomScoreCategories[$row->id] : 'none found'}}
                </td>

                {{--  JUDGES --}}
                <td class="border border-gray-200 px-1 text-left">
                    @foreach($roomJudges[$row->id] AS $judge)
                        <div class="@if($judge === 'none found') text-center @endif">{{ $judge }}</div>
                    @endforeach
                </td>

                {{-- TOLERANCE --}}
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->tolerance }}
                </td>

                <td class="border border-gray-200 px-1 text-center">
                    <x-buttons.edit
                        id="{{ $row->id }}"
                        livewire="1"
                    />
                </td>

                <td class="border border-gray-200 px-1 text-center">
                    <x-buttons.remove
                        id="{{ $row->id }}"
                        livewire="1"
                        message="Are you sure you want to remove this room?"
                    />
                </td>

            </tr>

        @empty
            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                No {{ $header }} found.
            </td>
        @endforelse
        </tbody>
    </table>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>
</div>
