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
                    <div>
                        {{ $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name'] }}
                    </div>
                </td>
                <td class="border border-gray-200 px-1">
                    <div>{{ $row['schoolName'] }}</div>
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['status'] }}
                </td>
                <td class="text-center border border-gray-200">
                    {{-- CLICKING EDIT-BUTTON OPENS EDIT-PARTICIPANT-FORM --}}
                    <div>
                        <button
                            wire:click="$set('showEditParticipantForm', {{ $row['id'] }} )"
                            type="button"
                            class="bg-indigo-600 text-white text-xs px-2 rounded-full hover:bg-indigo-700"
                        >
                            Edit
                        </button>
                    {{--                        <x-buttons.edit id="{{ $row['id'] }}" route="version.participant.edit"/>--}}
                </td>
                <td class="text-center border border-gray-200">
                    <x-buttons.remove id="{{ $row['id'] }}" livewire="1"/>
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
