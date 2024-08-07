@props([
    'columnHeaders',
    'header',
    'recordsPerPage',
    'registrationFee',
    'rows',
    'seniorYear',
    'sortAsc',
    'sortColLabel',
])
<div class="relative w-full">

    {{-- ADD NEW PAYMENT, EXPORT --}}
    <div class="flex justify-end items-center space-x-2 mt-2">

        <button type="button"
                wire:click="$set('addNewPayment', 1)"
                class="bg-green-500 text-white text-3xl px-2 rounded-lg"
                title="Add New"
                tabindex="-1"
        >
            +
        </button>

        <x-buttons.export/>
    </div>

    <div class="py-2 w-full flex justify-end">
        <a href="{{ route('pdf.estimate', 4) }}" class="text-blue-500" title="Download estimate form">
            <x-heroicons.tableCells/>
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
            <th class="border border-transparent px-1 sr-only">
                remove
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
                    {{ $row['last_name'] . ($row['suffix_name'] ? ' ' . $row['suffix_name'] : '') . ', ' . $row['first_name'] . $row['middle_name'] }} {{-- student name --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    ${{ number_format(($row['amount'] / 100), 2) }} {{-- ex. 25.00 --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['payment_type'] }} {{-- ex. cash --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['transaction_id'] }} {{-- ex. some string --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['comments'] }} {{-- ex. some comments --}}
                </td>
                <td class="text-center border border-gray-200">
                    <x-buttons.showEditForm id="{{ $row['id'] }}"/>
                </td>
                <td class="text-center border border-gray-200">
                    <x-buttons.remove id="{{ $row['id'] }}"/>
                </td>

            </tr>

        @empty
            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                No student payments found.
            </td>
        @endforelse
        </tbody>
    </table>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>
</div>
