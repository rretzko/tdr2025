<div class="px-4">
    <h2>{{ ucwords($header) }} for: <b>{{ $room->room_name }}</b></h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">
        <style>
            .narrow {
                width: 20rem;
            }
        </style>

        {{-- ADJUDICATION HEADER --}}
        <div class="flex flex-col bg-gray-100 border border-gray-500 p-2 rounded-lg shadow-lg">
            <label>Room Staff</label>
        </div>
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




