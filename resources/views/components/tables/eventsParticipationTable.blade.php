@props([
    'columnHeaders',
    'header',
    'recordsPerPage',
    'rows',
    'sortAsc',
    'sortColLabel',
])
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
                go-to
            </th>
        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $key => $row)
            <tr class=" odd:bg-green-50 @if($row->status !== 'active') text-gray-400 @endif">
                <td class="text-center">
                    {{ $key + 1 }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->eventName}}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->versionName }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->status }}
                </td>
                <td class="text-center border border-gray-200">

                    <a href="@if(in_array($row->status,['active','sandbox'])){{ route('participation.dashboard', ['version' => $row->id]) }} @else {{ route('participation.results', ['version' => $row->id]) }} @endif">
                        <button
                            type="button"
                            class="bg-yellow-600 text-white text-xs px-2 rounded-full hover:bg-yellow-700"
                        >
                            Go To
                        </button>
                    </a>
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
