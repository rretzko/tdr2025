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
                <td class="flex justify-center items-center border border-gray-200 border-b-transparent ">
                    <div class="flex flex-col space-y-1 md:w-5/6 lg:w-3/4 mx-0.5">
                        <button
                            type="button"
                            class="bg-yellow-600 text-white text-xs px-2 rounded-full hover:bg-yellow-700 "
                        >
                            Current
                        </button>

                        <button
                            type="button"
                            class="bg-yellow-600 text-white text-xs px-2 rounded-full hover:bg-yellow-700"
                        >
                            All
                        </button>

                        @can('create', \App\Models\Events\Versions\Version::class)
                            <button
                                type="button"
                                class="bg-yellow-600 text-white text-xs px-2 rounded-full hover:bg-yellow-700"
                            >
                                New
                            </button>
                        @endcan

                    </div>

                </td>
                <td class="text-center border border-gray-200">
                    <x-buttons.edit id="{{ $row['id'] }}" route="event.edit"/>
                </td>
                <td class="text-center border border-gray-200">
                    <x-buttons.remove id="{{ $row['id'] }}" livewire="1"/>
                </td>
            </tr>

        @empty
            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                No {{ $header }} found.
            </td>
        @endforelse
        </tbody>
    </table>

    <ul>
        <li>Edit Page Instructions</li>
        <li>Remove</li>
        <li>Export</li>
        <li>Event selection by roles</li>
        <li>New Event Page Instructions</li>
        <li>Link Ensembles to events</li>
        <li>Logo upload workflow and link</li>
    </ul>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>
</div>
