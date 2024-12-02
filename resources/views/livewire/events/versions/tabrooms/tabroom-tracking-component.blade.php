<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PROGRESS INDICATOR --}}
    <div class="flex flex-col">
        <div class="">{{ $progress['total']['count'] }} students</div>
        <div class="flex flex-row w-full border border-gray-600 rounded-lg shadow-lg">
            @foreach($progress AS $label => $data)
                @if(($label !== 'total') && ($data['count'] > 0))
                    <div class="flex {{$data['wpct']}}">
                        {{ $label }}: {{ $data['count'] }}
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div id="container" class="space-y-2">
        <h3>Room count: {{ count($rooms) }}</h3>

        @forelse($rooms AS $room)
            <div class="bg-green-100 rounded-lg px-2 font-semibold">
                {{ $room['roomName'] }}
            </div>
            <div class="flex flex-col ">
                <h4 class="ml-2 font-semibold border border-white border-b-gray-400 mb-2">
                    {{ $room['candidates']['voicePartDescr'] }}
                </h4>
                <div class="flex flex-row flex-wrap space-x-2 ml-2">
                    @forelse($room['candidates']['candidates'] AS $button)

                        <div class=" px-2 border border-gray-600 rounded-full mb-2 {{ $button['statusColors'] }}"
                             title="{{ $button['title'] }}"
                        >
                            {{ $button['candidateId'] }}
                        </div>

                    @empty
                        <div>No voice parts found.</div>
                    @endforelse
                </div>
            </div>
        @empty
            <div>No rooms found.</div>
        @endforelse
    </div>
</div>
