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

        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $key => $row)
            <tr class=" odd:bg-green-50 ">
                <td class='border border-gray-200 px-1 text-center'>{{ $loop->iteration }}</td>
                <td class='border border-gray-200 px-1'>
                    {{ $row['schoolName'] }}
                    <span class="ml-1 text-xs text-gray-400">( {{ date('h:i', \App\Models\Events\Versions\VersionTimeslot::where('version_id', 86)->where('school_id', $row['schoolId'])->first()->timeslot) }} )</span>
                </td>
                <td class='border border-gray-200 px-1'>{{ $row['teacherName'] }}</td>
                @foreach($row AS $itemKey => $item)
                    {{-- voice parts --}}
                    @if(is_numeric($itemKey))
                        <td
                            @class([
                                "border border-gray-200 px-1",
                                'text-center' => (is_numeric($item)),
                                'text-gray-300' => ($item === 0),
                            ])
                        >
                            {{ $item }}
                        </td>
                    @endif
                @endforeach
                <td class='border border-gray-200 px-1 text-center'>{{ $row['total'] }}</td>
                <td class='border border-gray-200 px-1 text-center'>{{-- @dd(array_key_exists($row['schoolId'], $assignedTimeslots) && ($assignedTimeslots[$row['schoolId']] == $timeslots[1]['selector'])) --}}
                    <select name="timeslot" wire:key="{{ $key }}"
                            wire:model.live="assignedTimeslotSelectors.{{ $row['schoolId'] }}">
                        @foreach($timeslots AS $ndx => $value)

                            <option value="{{ $ndx }}" @selected($assignedTimeslotSelectors[$row['schoolId']] == $ndx) >
                                {{ $value['selector'] }}
                            </option>

                        @endforeach
                    </select>
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
