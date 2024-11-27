<div class="px-4">
    <h2>{{ ucwords($header) }} for: <b>{{ $room->room_name }}</b></h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- STAFF SECTION --}}
        <div class="flex flex-col p-2 rounded-lg shadow-lg ml-2 mb-2">
            @include('components.forms.partials.adjudications.staff')
        </div>

        {{-- PROGRESS BAR --}}
        <div class="flex flex-col p-2 rounded-lg shadow-lg ml-2 mb-2">
            @include('components.forms.partials.adjudications.progressBar')
        </div>

        {{-- ADJUDICATION BUTTONS --}}
        <div class="flex flex-col p-2 rounded-lg shadow-lg ml-2 mb-2">
            @include('components.forms.partials.adjudications.adjudicationButtons')
            @if(count($referenceMaterials))
                @include('components.forms.partials.adjudications.referenceMaterials')
            @endif
        </div>

        {{-- RECORDING/AUDITION SCORING --}}
        @if(($form->sysId))

            {{-- RECORDINGS --}}
            @if($hasRecording)
                @include('components.forms.partials.adjudications.recordings')
            @endif

            <div class="flex flex-col sm:flex-row">
                <div class="flex flex-col p-2 rounded-lg shadow-lg ml-2 mb-2 w-full sm:w-1/2">
                    @include('components.forms.partials.adjudications.adjudicationForm')
                </div>


                {{-- JUDGE SUMMARY TABLE --}}
                <div class="flex flex-col bg-gray-100 p-2 rounded-lg shadow-lg ml-2 mb-2 w-full sm:w-1/2">
                    @include('components.forms.partials.adjudications.judgeSummaryTable')
                </div>
            </div>
        @endif

    </div>{{-- END OF ID=CONTAINER --}}

</div>




