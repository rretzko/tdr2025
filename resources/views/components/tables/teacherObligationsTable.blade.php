@props([
    'columnHeaders',
    'header',
    'membershipCardRequired',
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

            @if($membershipCardRequired)
                <th class="border border-transparent px-1 sr-only">
                    edit
                </th>
            @endif
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
                    <div>{{ $row->last_name . ($row->suffix_name ? ' ' . $row->suffix_name : '') . ', ' . $row->first_name . ' ' . $row->middle_name }}</div> {{-- student name --}}
                    <div class="ml-2 text-xs italic">
                        Accepted: {{ \Carbon\Carbon::parse($row->accepted)->format('M j, g:m a') }}</div>
                </td>
                <td class="border border-gray-200 px-1">
                    <div class="">{{ $row->schoolName }} </div>
                    <div class="ml-2 text-xs italic">Grades: {{ $row->grades }}</div>
                </td>

                @if($membershipCardRequired)
                    <td class="border border-gray-200 px-1">
                        <div>{{ \Carbon\Carbon::parse($row->expiration)->format('M j, Y') }}</div>
                    </td>
                    <td class="text-center border border-gray-200">
                        <x-buttons.edit id="$row['studentId'] " route="student.edit"/>
                    </td>
                @endif

                <td class="text-center border border-gray-200">
                    <x-buttons.remove id="$row['studentId'] " livewire="1"/>
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
