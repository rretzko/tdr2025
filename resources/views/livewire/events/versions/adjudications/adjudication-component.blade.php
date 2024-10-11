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
        </div>

        {{-- RECORDING/AUDITION SCORING --}}
        @if(($form->sysId))
            <div class="flex flex-col sm:flex-row">
                <div class="flex flex-col p-2 rounded-lg shadow-lg ml-2 mb-2 w-full sm:w-1/2">
                    @include('components.forms.partials.adjudications.adjudicationForm')
                </div>


                {{-- JUDGE SUMMARY TABLE --}}
                <div class="flex flex-col bg-gray-100 p-2 rounded-lg shadow-lg ml-2 mb-2 w-full sm:w-1/2">
                    <label>Judge Summary Table</label>
                </div>
            </div>
        @endif

        {{--        <div class="flex flex-col mt-4 p-2 border border-gray-300 rounded-lg shadow-lg w-full">--}}

        {{--            <div class="flex flex-col sm:flex-row">--}}

        {{--                --}}{{-- STAFF --}}
        {{--                <div class="my-4 p-2 border border-gray-300 rounded-lg shadow-lg ">--}}
        {{--                    <fieldset class="flex flex-col ">--}}
        {{--
        {{--                        @forelse($staff AS $judge)--}}
        {{--                            <div class="flex flex-row space-x-2">--}}
        {{--                                <div>--}}
        {{--                                    {{ $judge['name'] }}--}}
        {{--                                </div>--}}
        {{--                                <div>--}}
        {{--                                    {{ $judge['role'] }}--}}
        {{--                                </div>--}}
        {{--                                <div>--}}
        {{--                                    {{ $judge['email'] }}--}}
        {{--                                </div>--}}
        {{--                                <div>--}}
        {{--                                    {{ $judge['mobile'] }}--}}
        {{--                                </div>--}}
        {{--                            </div>--}}
        {{--                        @empty--}}
        {{--                            <div class="text-center">--}}
        {{--                                No staff found--}}
        {{--                            </div>--}}
        {{--                        @endforelse--}}
        {{--                    </fieldset>--}}
        {{--                </div>--}}

        {{--            </div>--}}

        {{--        </div>--}}

    </div>{{-- END OF ID=CONTAINER --}}

</div>




