<x-layouts.pages00>

    {{-- HEADER --}}
    <x-slot name="header">
        {{-- BREADCRUMBS --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ \Diglactic\Breadcrumbs\Breadcrumbs::render( 'hcProgramView', $hcEvent->id ?? '' ) }}
        </h2>
    </x-slot>

    <div
        class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

{{--        <x-pageInstructions.instructions instructions="none" firstTimer=false />--}}

        {{-- PAGE CONTENT --}}
        <div class="bg-gray-100 w-11/12 mt-1 px-2">

            {{-- PROGRAM HEADER --}}
            <div id="programHeader" class="pb-2 mb-4 border border-transparent border-b-gray-400">

                <div class="flex flex-row justify-between mr-4">
                    <h2 class="font-semibold text-black">{{ $hcEvent->name }}</h2>
                    <div id="prevNextButtons" class="flex flex-row space-x-2 text-xs mt-2">

                        {{-- PREV --}}
                        @if($prevEventId)
                            <a href="{{ route('hcEvent.show', $prevEventId) }}">
                                <button
                                    type="button"
                                    class="bg-blue-200 text-blue-800 px-4 rounded-lg shadow-lg border border-blue-800"
                                >
                                    {{ $prevYearOf }}
                                </button>
                            </a>
                        @endif

                        {{-- NEXT --}}
                        @if($nextEventId)
                            <a href="{{ route('hcEvent.show', $nextEventId) }}">
                                <button
                                    type="button"
                                    wire:click="clickGetProgram('next')"
                                    class="bg-blue-200 text-blue-800 px-4 rounded-lg shadow-lg border border-blue-800"
                                >
                                    {{ $nextYearOf }}
                                </button>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex flex-row space-x-2">
                    <label>Conductor: </label>
                    <div>{{ implode(',', $hcEvent->conductorNamesArray()) }}</div>
                </div>

                {{-- LINKS --}}
                <div class="flex flex-row w-full justify-start space-x-4">
                    {{-- PROGRAM LINK --}}
                    @if($hcEvent->program_link)
                        <div class="text-blue-500">
                            <a href="{{ $hcEvent->program_link }}" title="program image" target="_blank">
                                <x-heroicons.bookOpen />
                            </a>
                        </div>
                    @endif

                    {{-- IMAGE LINK --}}
                    @if($hcEvent->image_link)
                        <div class="text-blue-500">
                            <a href="{{ Storage::disk('s3')->url('njallstatechorus/images/' . $hcEvent->cleanImageLink()) }}" title="photo" target="_blank">
                                <x-heroicons.userGroup />
                            </a>

                        </div>
                    @endif

                    {{-- VIDEO LINK --}}
                    @if($hcEvent->video_link)
                        <div class="text-blue-500">
                            <a href="{{ $hcEvent->video_link }}" title="audio/video" target="_blank">
                                <x-heroicons.speakerWave />
                            </a>

                        </div>
                    @endif
                </div>
            </div>{{-- end of program links --}}

            <div id="programSelections" class="mb-4 pb-4 border border-transparent border-b-gray-400">
                <h3 class="font-semibold mb-2">Program Selections</h3>

                {{-- PROGRAM SELECTIONS TABLE --}}
                <table class="ml-4 w-2/3">
                    <thead>
                    <tr>
                        <th class="border border-gray-500 w-1/12">###</th>
                        <th class="border border-gray-500 w-7/12">
                            <div>title</div>
                            <div class="ml-4 text-sm italic">subtitle</div>
                        </th>
                        <th class="border border-gray-500 w-4/12">
                            artist(s)
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                @forelse($hcEvent->compositions() AS $composition)
                    <tr>
                        <td class="border border-gray-500 px-2 text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="border border-gray-500 px-2">
                            <div>{{ $composition['title'] }}</div>
                            <div class="ml-4 italic text-sm">{{ $composition['subtitle'] }}</div>
                        </td>
                        <td class="border border-gray-500 px-2">
                            {{ $composition['artist'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center border border-gray-500">No program selections found</td>
                    </tr>
                @endforelse
                    </tbody>
                </table>
            </div>{{-- end of program selections --}}

            {{-- PARTICIPANTS --}}
            <div id="participants" class="mb-4">
                <h3 class="font-semibold mb-2">Participants</h3>
                <div id="participantTables" class="flex flex-wrap ">
                    @forelse($hcEvent->getParticipantInstrumentOrderBys() AS $orderBy)
                        <table class="border border-gray-800 w-5/12 ml-4 mb-4 even:bg-gray-300">
                            <thead>
                            <tr>
                                <th colspan="3" class="px-1 border border-gray-600 bg-gray-400">
                                    {{ $orderBy->instrument_name }}
                                </th>
                            </tr>
                            <tr>
                                <th class="px-1 border border-gray-600 text-center">
                                    ###
                                </th>
                                <th class="px-1 border border-gray-600 text-center">
                                    Participant
                                </th>
                                <th class="px-1 border border-gray-600 text-center">
                                    School
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($hcEvent->getParticipants($orderBy->instrument_order_by) AS $participant)
                                <tr>
                                    <td class="px-1 border border-gray-600 text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-1 border border-gray-600">
                                        {{ $participant->full_name }}
                                    </td>
                                    <td class="px-1 border border-gray-600">
                                        {{ $participant->school_name }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @empty
                        <table>
                            <tbody>
                            <tr>
                                <th>
                                    No participants are found for {{ $hcEvent->name }}
                                </th>
                            </tr>
                            </tbody>
                        </table>
                    @endforelse
                </div>
            </div>{{-- end of participants --}}

        </div>{{-- end of page content --}}


    </div>
</x-layouts.pages00>
