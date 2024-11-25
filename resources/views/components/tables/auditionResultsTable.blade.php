@props([
    'feeParticipation',
    'hasContract',
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
            {{--            <th class="border border-transparent px-1 sr-only">--}}
            {{--                edit--}}
            {{--            </th>--}}
            {{--            <th class="border border-gray-200 px-1 sr-only">--}}
            {{--                remove--}}
            {{--            </th>--}}
        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $row)
            <tr class=" odd:bg-green-50 ">
                <td class="text-center">
                    {{ $loop->iteration + (($rows->currentPage() - 1) * $recordsPerPage) }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->last_name . ($row->suffix_name ? ' ' . $row->suffix_name : '') . ', ' . $row->first_name . ' ' . $row->middle_name }} {{-- student name --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->voicePartAbbr }} {{-- ex. si, aii, etc. --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    <div class="flex flex-row space-x-2 justify-center mx-auto">
                        <div>{{ $row->total }}</div>
                        <div wire:click="printResult({{ $row->candidateId }})"
                             class="text-blue-600 cursor-pointer"
                        >
                            <x-heroicons.printer/>
                        </div>
                    </div>
                </td>
                <td class="border border-gray-200 px-1  ">
                    @if($row->accepted)
                        <div class="flex items-center justify-center text-green-600">
                            <x-heroicons.check/>
                        </div>
                    @else
                        <div class=" flex items-center justify-center text-red-600">
                            <x-heroicons.xMark/>
                        </div>
                    @endif
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row->acceptance_abbr }}
                </td>

                @if($row->accepted && $hasContract)
                    <td class="border border-gray-200 px-1 text-center">

                        <div wire:click="printContract({{ $row->candidateId }})"
                             class="flex items-center justify-center text-green-600 cursor-pointer"
                        >
                            <x-heroicons.printer/>
                        </div>
                    </td>
                @endif

                @if($feeParticipation)
                    <td
                        @class([
                "border border-gray-200 px-1 text-center",
                'text-red-500' => (! $row->participationFee),
                'text-green-800' => ( $row->participationFee),
            ])
                    >
                        @if(!in_array($row->acceptance_abbr, ['inc','na','ns']))
                            {{ $row->participationFee ? 'PAID' : 'due' }}
                        @endif
                    </td>
                @endif
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
