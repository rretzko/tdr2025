<div class="relative overflow-x-auto">

    <table class=" px-4 shadow-lg w-full">
        <thead>
        <tr>
            <th class="border border-gray-400 px-1">
                ###
            </th>
            <th class="border border-gray-400 px-1">
                event
            </th>
            <th class="border border-gray-400 px-1">
                year of
            </th>
{{--            @foreach($columnHeaders AS $columnHeader)--}}
{{--                <th class='border border-gray-200 px-1'>--}}
{{--                    <button--}}
{{--                        @if($columnHeader['sortBy']) wire:click="sortBy('{{ $columnHeader['sortBy'] }}')" @endif--}}
{{--                        @class([--}}
{{--                        'flex items-center justify-center w-full gap-2 ',--}}
{{--                        'text-blue-500' => ($columnHeader['sortBy'])--}}
{{--                        ])--}}
{{--                    >--}}
{{--                        <div--}}
{{--                            @if($columnHeader['label'] === 'count')--}}
{{--                                title="count of selections"--}}
{{--                            @endif--}}
{{--                        >--}}
{{--                            {{ $columnHeader['label'] }}--}}
{{--                        </div>--}}
{{--                        @if($sortColLabel === $columnHeader['sortBy'])--}}
{{--                            @if($sortAsc)--}}
{{--                                <x-heroicons.arrowLongUp/>--}}
{{--                            @else--}}
{{--                                <x-heroicons.arrowLongDown/>--}}
{{--                            @endif--}}
{{--                        @endif--}}
{{--                    </button>--}}
{{--                </th>--}}
{{--            @endforeach--}}
            <th class="border border-transparent px-1 sr-only">
                view
            </th>
{{--            <th class="border border-gray-200 px-1 sr-only">--}}
{{--                remove--}}
{{--            </th>--}}
        </tr>
        </thead>
        <tbody>

        @forelse($hcEvents AS $row)

            <tr class="hover:bg-green-200">

                {{-- ### --}}
                <td class="border border-gray-400 px-1 text-center">
                    {{ $loop->iteration }}
                </td>

                {{-- event name --}}
                <td class="border border-gray-400 px-1 text-center cursor-help" title="">
                    {{ $row->name }}
                </td>

                {{-- year of --}}
                <td class="border border-gray-400 px-1">
                    {{ $row->year_of }}
                </td>

                {{-- performance date --}}
{{--                <td class="border border-gray-200 px-1 text-sm text-right min-w-24 ">--}}
{{--                    {{ $row->humanPerformanceDate }}--}}
{{--                </td>--}}

                {{-- count --}}
{{--                <td class="border border-gray-200 px-1 text-sm text-center min-w-24 ">--}}
{{--                    {{ $row->selectionCount }}--}}
{{--                </td>--}}

                {{-- tags --}}
{{--                <td class="border border-gray-200 px-1 text-sm">--}}
{{--                    {{ implode(', ', $row->tags->sortBy('name')->pluck('name')->toArray()) }}--}}
{{--                </td>--}}

                {{-- view column --}}
                <td class="text-center border border-gray-400">
                    <button wire:click="view({{ $row->id }})"
                            type="button"
                            class="bg-yellow-300 text-yellow-800 text-xs px-2 border border-yellow-500 rounded-full hover:bg-yellow-400"
                    >
                        View
                    </button>
                </td>
{{--                <td @class(["text-center border border-gray-200", "border-transparent" => ($row->name === 'Home Library')])>--}}
{{--                    @if($row->name !== "Home Library")--}}
{{--                        <x-buttons.edit id="{{ $row->id }}" :livewire="true" id="{{ $row->id }}"/>--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--                <td @class(["text-center border border-gray-200", "border-transparent" => ($row->name === 'Home Library')])>--}}
{{--                    @if($row->name !== "Home Library")--}}
{{--                        <x-buttons.remove id="{{ $row->id }}" livewire="1"--}}
{{--                                          message="Removing this program will unlink ALL associated items (ensembles, students, songs, etc) while leaving those items intact. Are you sure you want to remove this?"/>--}}
{{--                    @endif--}}
{{--                </td>--}}
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
