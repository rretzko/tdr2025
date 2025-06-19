<div>
    {{-- HEADER and PREVIOUS/NEXT BUTTONS --}}
    <div class="flex justify-between mb-1">
        <div>{{ $program->school->name . ' ' . ucwords($dto['header']) }}</div>
        <div class="flex flex-row justify-end space-x-2 text-sm">
            @if($previousProgramId)
                <button
                    wire:click="changeProgramId({{ $previousProgramId}})"
                    class="px-2 bg-blue-300 border border-black rounded-lg shadow-lg hover:bg-blue-400"
                >
                    Prev
                </button>
            @endif

            @if($nextProgramId)
                <button
                    wire:click="changeProgramId({{ $nextProgramId }})"
                    class="px-2 bg-green-300 border border-black rounded-lg shadow-lg hover:bg-green-400"
                >
                    Next
                </button>
            @endif
        </div>
    </div>

    {{-- PROGRAM HEADER --}}
    <div id="header" class="flex flex-col border border-white border-t-gray-500 border-b-gray-500 mb-2">

        {{-- TITLE --}}
        <div class="flex flex-row space-x-2">
            <label class="w-20">Title</label>
            <div class="data font-semibold">{{ $program->title }}</div>
        </div>

        {{-- SUBTITLE --}}
        @if($program->subtitle)
            <div class="flex flex-row space-x-2">
                <label class="w-20">Subtitle</label>
                <div class="data font-semibold">{{ $program->subtitle }}</div>
            </div>
        @endif

        {{-- PERFORMANCE DATE --}}
        <div class="flex flex-row space-x-2">
            <label class="w-20">Perf.Date</label>
            <div class="data font-semibold">{{ $program->humanPerformanceDate }}</div>
        </div>

    </div>{{-- end of program header --}}
</div>
