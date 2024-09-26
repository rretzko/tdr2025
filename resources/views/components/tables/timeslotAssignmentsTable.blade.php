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
                <td class='border border-gray-200 px-1'>{{ $row['schoolName'] }}</td>
                <td class='border border-gray-200 px-1'>{{ $row['teacherName'] }}</td>
                @foreach($row AS $item)
                    @if(is_numeric($item))
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
                <td class='border border-gray-200 px-1 text-center'>
                    <select name="timeslot" wire:key="{{ $key }}" wire:model.live="timeslot">
                        @foreach($timeslots AS $ndx=>$value)
                            <option value="{{ $key .'_' . $ndx}}">
                                {{ $value }}
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
