<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PROGRESS INDICATOR --}}
    @include('components.forms.partials.tabrooms.tracking.progressBar')


    <div id="container" class="space-y-2">

        {{-- ROOM SELECTOR --}}
        @include('components.forms.partials.tabrooms.tracking.roomSelector')

        <h3>Room count: {{ $studentCount }}</h3>

        @forelse($rooms AS $room)
            <div class="bg-green-100 rounded-lg px-2 font-semibold">
                {{ $room['roomName'] }}
            </div>
            <div class="flex flex-col ">
                <h4 class="ml-2 font-semibold border border-white border-b-gray-400 mb-2">
                    {{ $room['candidates']['voicePartDescr'] }}
                </h4>
                <div class="flex flex-row flex-wrap ml-2">
                    @forelse($room['candidates']['candidates'] AS $button)

                        <div class=" px-2 border border-gray-600 rounded-full mb-2 mr-1 {{ $button['statusColors'] }}"
                             title="{{ $button['title'] }}"
                        >
                            <span class="text-red-600">
                                @if(! $button['tolerance'])
                                    *
                                @endif
                            </span>
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
