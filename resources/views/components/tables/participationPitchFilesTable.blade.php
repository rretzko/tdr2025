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
                    {{ $loop->iteration }}
                </td>
                <td class="border border-gray-200 px-1">
                    <div>{{ $row['voicePartDescr'] ?: 'All'}}</div>
                </td>
                <td class="border border-gray-200 px-1">
                    <div>{{ $row['file_type'] }}</div>
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['description'] }}
                </td>
                <td class="text-center">
                    @if(\Illuminate\Support\Str::endsWith($row['url'], '.pdf'))
                        <a href="https://auditionsuite-production.s3.amazonaws.com/{{ $row['url'] }}"
                           class="text-blue-500 underline italic"
                           target="_blank"
                        >
                            {{ $row['description'] }}
                        </a>
                    @else
                        <audio id="audioPlayer" class="mx-auto" controls style="display:block;">

                            <source id="audioSource"
                                    src="https://auditionsuite-production.s3.amazonaws.com/{{ $row['url'] }}"
                                    type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    @endif
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
