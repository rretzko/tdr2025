<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container" class="space-y-2">

        <div class="ml-2 p-2 rounded-lg shadow-lg flex flex-col">
            <h2 class="font-semibold">Filter by candidate id or name</h2>
            @include('components.forms.partials.tabrooms.filterByCandidateIdOrName')
        </div>

        @if(! strlen($candidateError))
            <div class="ml-2 p-2 border-gray-600 rounded-lg shadow-lg">
                <h2 class="font-semibold">Current scores for room with (tolerance)</h2>
                @if((! is_null($rooms)) && $rooms->count())
                    @include('components.forms.partials.tabrooms.displayCandidateScoringByRoom')
                @else
                    @if($candidateId)
                        <div class="text-red-600 ml-2 mt-1 text-xs italic">
                            No rooms found for {{ $candidateId }} as {{ $candidateVoicePartDescr }}.
                        </div>
                    @endif
                @endif
            </div>

            {{-- SEARCH FOR CANDIDATE BY ID OR LAST NAME --}}
            <div class="ml-2 mt-2 p-2 bg-white border-gray-600 rounded-lg shadow-lg">
                <h2 class="font-semibold">Form for updating {{ $candidateName }}'s scores.</h2>

                {{-- RECORDINGS --}}
                <div>
                    @if($hasRecordings && $candidateId)
                        @include('components.forms.partials.adjudications.recordings')
                    @endif
                </div>

                @if($candidateId)

                {{-- ADJUDICATOR SELECTORS --}}
                <div
                    class="flex flex-row ml-4 mt-4 mb-2 space-x-8 border border-white border-t-gray-600 border-b-gray-600">
                    <h3>Select adjudicator</h3>
                    @if($judges)
                        @foreach($judges AS $radioJudge)
                            <div class="space-x-2">
                                <input type="radio" wire:model.live="judgeId" value="{{$radioJudge->id }}"
                                       wire:key="judge_{{ $radioJudge->id }}"/>
                                <label>{{ $radioJudge->user->name }}</label>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- ADJUDICATION FORM --}}
                <div>
                    @include('components.forms.partials.tabrooms.adjudicationForm')
                </div>
            @endif
        </div>
        @endif
    </div>{{-- END OF ID=CONTAINER --}}

</div>




