<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ $version->short_name . ' ' . ucwords($dto['header']) }} ({{ $rows->count() }})</div>
        </div>

        {{-- FILTER RESULTS --}}
        <div class="my-2">
            <button class="bg-green-300 px-2 text-green-900 rounded-full border border-green-900 shadow-lg text-sm"
                    wire:click="toggleRows">
                @if($showRegistered)
                    Show All candidates
                @else
                    Show Registered Candidates only
                @endif
            </button>
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
                        <td>
                            <a href="{{ route('candidate', ['candidate' => $row->candidateId])}}"
                               class="text-blue-500 hover:underline">
                                {{ $row->last_name . ', ' . $row->first_name . ' ' . $row->middle_name }}
                            </a>
                        </td>
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
                                    <label
                                        @class([
                                            "text-center text-xs italic pb-1",
                                            "text-green-500" => strlen($row->scalesApproved),
                                            "bg-yellow-400 text-black w-fit rounded-full px-2 mx-auto mb-1 " => is_null($row->scalesApproved),
                                    ])
                                    >
                                        @if(strlen($row->scalesApproved))
                                            Approved: {{ $row->scalesApproved }}
                                        @else
                                            Pending...
                                        @endif

                                    </label>
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
                                    <label
                                        @class([
                                            "text-center text-xs italic pb-1",
                                            "text-green-500" => strlen($row->soloApproved),
                                            "bg-yellow-400 text-black w-fit rounded-full px-2 mx-auto mb-1 " => is_null($row->soloApproved),
                                    ])
                                    >
                                        @if(strlen($row->soloApproved))
                                            Approved: {{ $row->soloApproved }}
                                        @else
                                            Pending...
                                        @endif

                                    </label>
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
                                    <label
                                        @class([
                                            "text-center text-xs italic pb-1",
                                            "text-green-500" => strlen($row->quintetApproved),
                                            "bg-yellow-400 text-black w-fit rounded-full px-2 mx-auto mb-1 " => is_null($row->quintetApproved),
                                    ])
                                    >
                                        @if(strlen($row->quintetApproved))
                                            Approved: {{ $row->quintetApproved }}
                                        @else
                                            Pending...
                                        @endif

                                    </label>
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
