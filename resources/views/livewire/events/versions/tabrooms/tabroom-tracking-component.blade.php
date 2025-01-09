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

            {{-- JUDGE STATUS BARS --}}
            <div class="flex flex-col space-y-1">
                @forelse($judgeProgress AS $judge)
                    <div class="flex flex-row w-full">
                        <div title="{{ $judge['judgeName'] }}" class="w-[8rem] border border-black px-2">
                            {{ $judge['judgeShortName'] }}
                        </div>
                        <div class="flex flex-row w-full border border-black ">
                            @if($judge['pending']['count'])
                                <div title="pending"
                                     class="{{ $barFormats['pending'] }} w-[ {{$judge['pending']['pct'] }} text-center border border-black">
                                    {{ $judge['pending']['count'] }}
                                </div>
                            @endif
                            @if($judge['wip']['count'])
                                <div title="wip"
                                     class="{{ $barFormats['wip'] }}  w-[{{ $judge['wip']['pct'] }}] text-center border border-black">
                                    {{ $judge['wip']['count'] }}
                                </div>
                            @endif
                            @if($judge['completed']['count'])
                                <div title="completed"
                                     class="{{ $barFormats['completed'] }} w-[{{ $judge['completed']['pct'] }}] text-center border border-black">
                                    {{ $judge['completed']['count'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div>No judges found.</div>
                @endforelse
            </div>

            {{-- ADJUDICATION BUTTONS --}}
            <div class="flex flex-col ">
                <h4 class="ml-2 font-semibold border border-white border-b-gray-400 mb-2">
                    {{ $room['candidates']['voicePartDescr'] }}
                </h4>

                {{-- BUTTONS --}}
                <div class="flex flex-row flex-wrap ml-2">
                    @forelse($room['candidates']['candidates'] AS $button)

                        <div
                            class=" px-2 border border-gray-600 rounded-full mb-2 mr-1 font-mono {{ $button['statusColors'] }}"
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
