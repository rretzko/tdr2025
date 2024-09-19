@props([
    'columnHeaders',
    'header',
    'rows',
    'sortAsc',
    'sortColLabel',
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

                {{-- PAPER BACKUP --}}
                <td class="border border-gray-200 px-1">
                    <div class="flex justify-center">
                        <button
                            type="button"
                            wire:click="pdf('backup', {{ $row->id }})"
                            class="text-blue-500"
                        >
                            <x-heroicons.document/>
                        </button>
                    </div>
                </td>

                {{-- CSV BACKUP --}}
                <td class="border border-gray-200 px-1">
                    <div class="flex justify-center">
                        <x-heroicons.tableCells/>
                    </div>
                </td>

                {{--  MONITOR CHECKLIST --}}
                <td class="border border-gray-200 px-1">
                    <div class="flex justify-center">
                        <x-heroicons.documentCheck/>
                    </div>
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
