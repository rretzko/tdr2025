<div
    class="flex flex-col text-xs md:text-lg ml-4 sm:flex-row sm:space-x-2 sm:flex-wrap space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW BUTTON --}}
        <div class="mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
        </div>

        {{-- BUTTONS --}}
        <div id="buttons" class="flex flex-col space-y-2 ml-4">

            <x-buttons.generateSandboxRows :testFactor="$eventId" buttonLabel="Generate Event" legendLabel="event id"/>

            <x-buttons.generateSandboxRows :testFactor="$versionId" buttonLabel="Generate Version"
                                           legendLabel="version id"/>

            <x-buttons.generateSandboxRows :testFactor="$participantCount" buttonLabel="Generate Participants"
                                           legendLabel="participant count"/>

            <x-buttons.generateSandboxRows :testFactor="$candidateCount" buttonLabel="Generate Candidates"
                                           legendLabel="candidate count"/>

            <x-buttons.generateSandboxRows :testFactor="$registrantCount" buttonLabel="Generate Registrants"
                                           legendLabel="registrant count"/>

            <x-buttons.generateSandboxRows :testFactor="$roomCount" buttonLabel="Generate Rooms"
                                           legendLabel="room count"/>

            <x-buttons.generateSandboxRows :testFactor="$judgeCount" buttonLabel="Generate Judges"
                                           legendLabel="judge count"/>

            <x-buttons.generateSandboxRows :testFactor="$scoreCount" buttonLabel="Generate Scores"
                                           legendLabel="score count" click="generateScores"/>

            <button class="flex w-fit min-w-1/4 px-2 rounded-lg shadow-lg bg-yellow-300">
                Generate Results
            </button>

            <button class="flex w-fit min-w-1/4 px-2 rounded-lg shadow-lg bg-yellow-300">
                Generate Cut-Offs
            </button>

            <button class="flex w-fit min-w-1/4 px-2 rounded-lg shadow-lg bg-yellow-300">
                Generate Results PDFs
            </button>

            <button class="flex w-fit min-w-1/4 px-2 rounded-lg shadow-lg bg-yellow-300">
                Generate Rehearsal Manager csv
            </button>

        </div>

    </div>{{-- END OF PAGE CONTENT --}}

</div>






