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
                <td class="border border-gray-200 px-1  ">
                    @if($row['active'])
                        <div class="flex items-center justify-center text-green-600">
                            <x-heroicons.check/>
                        </div>
                    @else
                        <div class=" flex items-center justify-center text-red-600">
                            <x-heroicons.xMark/>
                        </div>
                    @endif
                </td>
                <td class="text-center border border-gray-200">
                    <x-buttons.edit id="{{ $row['schoolStudentId'] }}" route="student.edit"/>
                </td>
                <td class="text-center border border-gray-200">
                    <x-buttons.remove id="{{ $row['schoolStudentId'] }}" livewire="1"/>
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
