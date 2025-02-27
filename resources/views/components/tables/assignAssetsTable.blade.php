@props([
    'columnHeaders',
    'ensembleAssets',
    'header',
    'inventoryAdds',
    'inventoryEdits',
    'inventoryErrors',
    'rows',
    'sortAsc',
    'sortColLabel',
])
<div class="relative overflow-x-auto">

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
                    'text-gray-400, bg-gray-50, odd:bg-gray-50' => (! ($row->status === 'active')),
                ])
            >
                <td class="border border-gray-200 px-1 text-center">
                    {{ $loop->iteration }}
                </td>
                <td @class([
                    "border border-gray-200 px-1",
                    'text-red-300' => ($row->status === 'removed')
                    ])
                >
                    <div>
                        <button wire:click="clickName({{ $row->studentId }})" class="text-blue-500 hover:underline">
                            {{ $row->last_name }}, {{ $row->first_name }} {{ $row->middle_name }}
                        </button>
                    </div>
                </td>
                <td @class([
                        "border border-gray-200 px-1 text-center",
                        'text-red-300' => ($row->status === 'removed')
                        ])
                >
                    {{ $row->status }}
                </td>
                <td @class([
                        "border border-gray-200 px-1 text-center",
                        'text-red-300' => ($row->status === 'removed')
                        ])
                >
                    {{ $row->calcGrade }}/{{ $row->class_of }}
                </td>
                @for($i=0; $i<$ensembleAssets->count(); $i++)
                    <td class="text-center" wire:key="{{$row->studentId}}_{{$i}}">
                        @php($arrayId = $row->studentId . '_' . $i)
                        <input
                            type="text"
                            wire:model="inventoryEdits.{{ $arrayId }}"
                            class="w-[6rem] h-2.5"
                        />
                        {{-- ERROR MESSAGE --}}
                        <div class="text-sm text-red-500">
                            @if(isset($inventoryErrors[$arrayId]))
                                <ul>
                                    @foreach($inventoryErrors[$arrayId] AS $mssg)
                                        <li>{{ $mssg }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        {{-- SUCCESS MESSAGE --}}
                        <div class="text-sm text-green-700">
                            @if(isset($inventoryAdds[$arrayId]))
                                {{ $inventoryAdds[$arrayId] }}
                            @endif
                        </div>
                    </td>
                @endfor
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

    <div class="flex flex-row space-x-2">
        <x-buttons.submit type="button" :livewire="true" wireClick="save"/>
        <x-buttons.submitAndStay value="submit and stay on page"/>
    </div>


    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>

</div>
