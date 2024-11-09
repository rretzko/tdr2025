<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container" class="space-y-2">

        {{-- PARTICIPANT COUNT SUMMARY TABLE --}}
        <div>
            @include('components.tables.participantCountSummaryTable')
        </div>

        {{-- SCORE CUT-OFF STACKS --}}
        <div>
            @include('components.tables.scoreCutoffsTable')
        </div>
    </div>
</div>
