@props([
    'columnHeaders',
    'header',
    'rows',
    'sortAsc',
    'sortColLabel',
    'roomJudges',
    'roomScoreCategories',
    'roomVoiceParts',
])
<div class="relative">

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
                <td class="border border-gray-200 px-1 text-center">
                    {{ ($roomJudges[$row->id]) ? $roomJudges[$row->id] : 'none found'}}
                </td>

                {{-- TOLERANCE --}}
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->tolerance }}
                </td>

                <td class="border border-gray-200 px-1 text-center">
                    <button
                        wire:click="edit({{ $row->id }})"
                        class="bg-indigo-500 text-white text-xs px-2 rounded-lg"
                    >
                        Edit
                    </button>
                </td>

                <td class="border border-gray-200 px-1 text-center">
                    <button
                        wire:click="remove({{ $row->id }})"
                        class="bg-red-500 text-white text-xs px-2 rounded-lg"
                    >
                        Remove
                    </button>
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
