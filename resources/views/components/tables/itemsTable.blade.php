<div class="relative overflow-x-auto">

    {{-- Success Message --}}
    @if(session()->has('successMessage'))
        <div class="bg-green-100 text-sm text-green-800 mb-2 px-2 rounded-lg w-fit">
            {{ session('successMessage') }}
        </div>
    @endif

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

            <tr class="odd:bg-green-50 hover:bg-green-100">
                <td class="border border-gray-200 px-1 text-center">
                    {{ $loop->iteration }}
                </td>
                <td class="border border-gray-200 px-1 text-center cursor-help">
                    {{ $row['item_type'] }}
                </td>
                <td class="border border-gray-200 px-1 text-center cursor-help">
                    {{ $row['title'] }}
                </td>
                {{--                <td class="border border-gray-200 px-1">--}}
                {{--                    {{ $row->schoolName ?? $row->name}}--}}
                {{--                </td>--}}
                <td class="text-center border border-gray-200 px-1">
                    <x-buttons.edit id="{{ $row['id'] }}" :livewire="true" id="{{ $row['id'] }}"/>
                </td>
                <td class="text-center border border-gray-200 px-1">
                    <x-buttons.remove id="{{ $row['id'] }}" livewire="1"
                                      message="Are you sure you want to remove this library item?"/>
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
