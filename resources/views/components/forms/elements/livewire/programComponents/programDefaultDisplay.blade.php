<div id="displayPage"
     class="w-full md:w-1/2 mr-4 shadow-lg p-2 border border-gray-200 border-r-gray-100 border-b-gray-100 rounded-lg"
>
    {{-- PROGRAM TITLE --}}
    <div class="text-center italic ">
        {{ $program->title }}
    </div>

    {{-- PROGRAM SUBTITLE --}}
    @if(strlen($program->subtitle))
        <div class="text-center italic text-sm">
            {{ $program->subtitle }}
        </div>
    @endif

    {{-- PERFORMANCE DATE --}}
    <div class="text-center italic text-xs">
        {{ $program->humanPerformanceDateLong }}
    </div>

    {{-- PROGRAM TABLE --}}
    <div id="programSelectionsTable">
        {!! $selections !!}
    </div>
</div>
