@props([
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
            <tr class=" odd:bg-green-50 ">
                <td class="text-center">
                    {{ $loop->iteration + (($rows->currentPage() - 1) * $recordsPerPage) }}
                </td>
                <td class="border border-gray-200 px-1">
                    <div>{{ $row['name'] }}</div>
                    <div class="ml-2 text-xs italic">{{ $row['short_name'] }}</div>
                    <div class="ml-2 text-xs italic">{{ $row['organization'] }}</div>
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['grades'] }} {{-- ex. 2026 (11th grade) --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['ensemble_count'] }} {{-- ex. baritone --}}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['status'] }} {{-- ex. 64 (5' 4") --}}
                </td>
                <td class="mx-auto text-center border border-gray-200 border-b-transparent">
                    <div class="flex flex-col space-y-1 md:w-5/6 lg:w-3/4 mx-2 lg:mx-4">
                        {{--                        @can('edit', )--}}
                        <a href="{{ route('version.current', ['event' => $row['id']]) }}">
                            <button
                                type="button"
                                class="bg-yellow-600 text-white text-xs px-2 rounded-full hover:bg-yellow-700 "
                            >
                                Current
                            </button>
                        </a>
                        {{--                        @endcan--}}

                        {{--                        @can('edit', )--}}
                        <button
                            type="button"
                            class="bg-yellow-600 text-white text-xs px-2 rounded-full hover:bg-yellow-700"
                        >
                            All
                        </button>
                        {{--                        @endcan--}}

                        @can('create', [new \App\Models\Events\Versions\Version(), $row['id']])
                            <a href="{{ route('version.profile', ['event' => $row['id']]) }}">
                                <button
                                    type="button"
                                    class="bg-yellow-600 text-white text-xs px-2 rounded-full hover:bg-yellow-700"
                                >
                                    New
                                </button>
                            </a>
                        @endcan

                    </div>

                </td>
                <td class="text-center border border-gray-200">
                    @can('update', \App\Models\Events\Event::find($row['id']))
                        <x-buttons.edit id="{{ $row['id'] }}" route="event.edit"/>
                    @endcan
                </td>
                <td class="text-center border border-gray-200">
                    @can('delete', \App\Models\Events\Event::find($row['id']))
                        <x-buttons.remove id="{{ $row['id'] }}" livewire="1"/>
                    @endcan
                </td>
            </tr>

        @empty
            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                No {{ $header }} found.
            </td>
        @endforelse
        </tbody>
    </table>


    <div>
        <h2 class="font-semibold underline mt-4">To-Dos</h2>
        <ul>
            <li>Event selection by roles</li>
        </ul>
    </div>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>
</div>
