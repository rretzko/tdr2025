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
            {{-- WIRE:KEY here --}}
            <tr class=" odd:bg-green-50 " wire:key="pitch-file-{{ $loop->iteration }}">
                <td class="text-center">
                    {{ $loop->iteration + (($rows->currentPage() - 1) * $recordsPerPage) }}
                </td>
                <td class="border border-gray-200 px-1">
                    <div>{{ $row['descr'] ?: 'All'}}</div>
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
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['order_by'] }}
                </td>
                <td class="text-center border border-gray-200">
                    {{-- CLICKING EDIT-BUTTON OPENS EDIT-PARTICIPANT-FORM --}}
                    <div>
                        <button
                            wire:click="$set('showEditForm', {{ $row['id'] }} )"
                            type="button"
                            class="bg-indigo-600 text-white text-xs px-2 rounded-full hover:bg-indigo-700"
                        >
                            Edit
                        </button>
                    </div>
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

    {{--    <script>--}}
    {{--    function playAudio(url) {--}}
    {{--        console.log('https://auditionsuite-production.s3.amazonaws.com/' + url);--}}
    {{--    var audioPlayer = document.getElementById('audioPlayer');--}}
    {{--    var audioSource = document.getElementById('audioSource');--}}

    {{--    // Set the source of the audio player--}}
    {{--    audioSource.src = 'https://auditionsuite-production.s3.amazonaws.com/' + url;--}}

    {{--    // Load the new source--}}
    {{--    audioPlayer.load();--}}

    {{--    // Play the audio--}}
    {{--    audioPlayer.play();--}}

    {{--    // Optionally, display the audio controls--}}
    {{--    audioPlayer.style.display = 'block';--}}
    {{--    }--}}
    {{--    </script>--}}

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>
</div>
