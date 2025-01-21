<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ $version->short_name . ' ' . ucwords($dto['header']) }} ({{ $rows->count() }})</div>
        </div>

        {{-- RECORDINGS TABLE --}}
        <div id="recordingsTable">
            @php {{ $voiceOrderBy=0; }} @endphp
            <style>
                td, th {
                    border: 1px solid black;
                    padding: 0 0.25rem;
                    overflow-x: hidden;
                }
            </style>
            <table class="">
                <thead>
                <tr>
                    <th>###</th>
                    <th>name</th>
                    <th>voice part</th>
                    <th>scales</th>
                    <th>solo</th>
                    <th>quintet</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rows AS $row)
                    @if(! $voiceOrderBy)
                        @php($voiceOrderBy = $row->order_by)
                    @endif
                    @if($voiceOrderBy !== $row->order_by)
                        @php($voiceOrderBy = $row->order_by)
                        <tr>
                            <td colspan="6" class="bg-gray-300 text-sm font-semibold uppercase">
                                {{ $row->voicePartDescr }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $row->last_name . ', ' . $row->first_name . ' ' . $row->middle_name }}</td>
                        <td class="text-center">{{ $row->voicePart }}</td>
                        <td class="text-center">
                            @if($row->scalesUrl)
                                <div
                                    class="flex flex-col text-white px-1 border border-r-gray-600 rounded-lg bg-gray-800"
                                    wire:key="{{ $row->scalesUrl }}"
                                >
                                    <label class="text-center text-xs">{{ $row->scalesFileType }}
                                        ({{ substr($row->scalesUrl,-3) }})</label>
                                    <audio id="audioPlayer-{{ $row->scalesFileType }}" class="mx-auto" controls
                                           style="display: block; justify-self: start; margin-bottom: 0.50rem; width: 250px;">
                                        <source id="audioSource-{{ $row->scalesFileType }}"
                                                src="https://auditionsuite-production.s3.amazonaws.com/{{ $row->scalesUrl }}"
                                                type="audio/mpeg"
                                        >
                                        " Your browser does not support the audio element. "
                                    </audio>
                                </div>
                            @else
                                Scales file not uploaded.
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row->soloUrl)
                                <div
                                    class="flex flex-col text-white px-1 border border-r-gray-600 rounded-lg bg-gray-800"
                                    wire:key="{{ $row->soloUrl }}"
                                >
                                    <label class="text-center text-xs">{{ $row->soloFileType }}
                                        ({{ substr($row->soloUrl,-3) }})</label>
                                    <audio id="audioPlayer-{{ $row->soloFileType }}" class="mx-auto" controls
                                           style="display: block; justify-self: start; margin-bottom: 0.50rem; width: 250px;">
                                        <source id="audioSource-{{ $row->soloFileType }}"
                                                src="https://auditionsuite-production.s3.amazonaws.com/{{ $row->soloUrl }}"
                                                type="audio/mpeg"
                                        >
                                        " Your browser does not support the audio element. "
                                    </audio>
                                </div>
                            @else
                                Solo file not uploaded.
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row->quintetUrl)
                                <div
                                    class="flex flex-col text-white px-1 border border-r-gray-600 rounded-lg bg-gray-800"
                                    wire:key="{{ $row->quintetUrl }}"
                                >
                                    <label class="text-center text-xs">{{ $row->quintetFileType }}
                                        ({{ substr($row->quintetUrl,-3) }})</label>
                                    <audio id="audioPlayer-{{ $row->quintetFileType }}" class="mx-auto" controls
                                           style="display: block; justify-self: start; margin-bottom: 0.50rem; width: 250px;">
                                        <source id="audioSource-{{ $row->quintetFileType }}"
                                                src="https://auditionsuite-production.s3.amazonaws.com/{{ $row->quintetUrl }}"
                                                type="audio/mpeg"
                                        >
                                        " Your browser does not support the audio element. "
                                    </audio>
                                </div>
                            @else
                                Quintet file not uploaded.
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No recordings found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
