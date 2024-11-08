<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container" class="space-y-2">
        <h3>Room count: {{ count($rooms) }}</h3>

        @forelse($rooms AS $room)
            <div class="bg-green-100 rounded-lg px-2 font-semibold">
                {{ $room['roomName'] }}
            </div>
            <div class="flex flex-col ">
                @forelse($room['candidates'] AS $candidate)
                    <h4 class="ml-2 font-semibold border border-white border-b-gray-400 mb-2">
                        {{ $candidate['voicePartDescr'] }}
                    </h4>
                    <div class="flex flex-row flex-wrap space-x-2 ml-2">
                        @forelse($candidate['candidates'] AS $button)
                            <div class=" px-2 border border-gray-600 rounded-full mb-2 {{ $button['statusColors'] }}"
                                 title="{{ $button['title'] }}"
                            >
                                {{ $button['candidateId'] }}
                            </div>
                        @empty
                            <div>No candidates found.</div>
                        @endforelse
                    </div>
                @empty
                    <div>No voice parts found.</div>
                @endforelse
            </div>
        @empty
            <div>No rooms found.</div>
        @endforelse
    </div>
</div>
