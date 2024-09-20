@props([
    'columnHeaders',
    'header',
    'recordsPerPage',
    'registrationFee',
    'rows',
    'seniorYear',
    'sortAsc',
    'sortColLabel',
    'versionId',
])
<div class="relative w-11/12">

    <div class="py-2 w-full flex justify-end">
        <a href="{{ route('pdf.estimate', $versionId) }}" class="text-blue-500" title="Download estimate form">
            <x-heroicons.document/>
        </a>
    </div>

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
        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $row)
            <tr class=" odd:bg-green-50 ">
                <td class="text-center">
                    {{ $loop->iteration }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->last_name . ($row->suffix_name ? ' ' . $row->suffix_name : '') . ', ' . $row->first_name . $row->middle_name }} {{-- student name --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->voicePartDescr }} {{-- ex. baritone --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->grade }} {{-- ex. 11 --}}
                </td>
                <td @class([
        "border border-gray-200 px-1 text-center",
        "text-gray-300" => ($row->payment == 0),
        ])
                >
                    ${{ number_format($row->payment, 2) }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    ${{ $registrationFee * $loop->iteration }} {{-- ex. 25.00 --}}
                </td>
            </tr>

        @empty
            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                No registrants found.
            </td>
        @endforelse
        </tbody>
    </table>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>
</div>
