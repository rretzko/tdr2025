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
                    <div>{{ $row['file_type'] ?: 'All'}}</div>
                </td>
                <td class="border border-gray-200 px-1">
                    <div>{{ $row['segment'] }}</div>
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['abbr'] }}
                </td>
                <td class="border border-gray-200 text-center px-1">
                    {{ $row['order_by'] }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['best'] }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['worst'] }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['multiplier'] }}
                </td>
                <td class="border border-gray-200 px-1 text-center">
                    {{ $row['tolerance'] }}
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
