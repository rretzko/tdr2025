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

            <tr class="odd:bg-green-50">
                <td class="border border-gray-200 px-1 text-center">
                    {{ $loop->iteration }}
                </td>
                <td class="border border-gray-200 px-1 text-center cursor-help" title="">
                    <a href="{{ route('library.items', ['library' => $row->id]) }}"
                       class="text-blue-600 font-bold hover:underline">
                        {{ $row->name }}
                    </a>
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->schoolName ?? $row->name}}
                </td>
                <td @class(["text-center border border-gray-200", "border-transparent" => ($row->name === 'Home Library')])>
                    @if($row->name !== "Home Library")
                        <x-buttons.edit id="{{ $row->id }}" :livewire="true" id="{{ $row->id }}"/>
                    @endif
                </td>
                <td @class(["text-center border border-gray-200", "border-transparent" => ($row->name === 'Home Library')])>
                    @if($row->name !== "Home Library")
                        <x-buttons.remove id="{{ $row->id }}" livewire="1"
                                          message="Removing this library will unlink ALL associated items (music, books, cds, etc). Are you sure you want to remove this?"/>
                    @endif
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
