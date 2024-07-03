@props([
    'columnHeaders',
    'header',
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
//                    'text-gray-400, bg-gray-50, odd:bg-gray-50' => (! ($row->status === 'active')),
                ])
            >
                <td class="border border-gray-200 px-1 text-center">
                    {{ $loop->iteration }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->name }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->item_id }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->size }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->color }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->comments }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->status }}
                </td>
                <td class="text-center border border-gray-200">
                    <x-buttons.edit id="{{ $row->id }}" route="inventory.edit"/>
                </td>
                <td class="text-center border border-gray-200">
                    <x-buttons.remove id="{{ $row->id }}" livewire="1"/>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                    No {{ $header }} found.
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>

</div>
