<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }}</div>
        </div>

        {{-- FILTERS and TABLE --}}
        <div class="flex flex-row">

            <div class="flex flex-col w-full">
                <div>{{ $content }}</div>
                @if($obligationFile)
                    <$obligationFile />
                @endif
                {{-- Accept/Reject buttons --}}
                <div class="m-4">
                    {{--                    @if($dto['obligationAccepted'])--}}
                    {{--                        <a href="{{ route('obligation.update', ['version' => $dto['versionId'], 'accepted' => 0]) }}">--}}
                    {{--                            <button class="p-2 bg-red-500 text-white rounded-full">--}}
                    {{--                                I Reject These Obligations--}}
                    {{--                            </button>--}}
                    {{--                        </a>--}}
                    {{--                        <div class="mt-2 text-green-500 italic text-sm">--}}
                    {{--                            Obligation accepted on: {{ $dto['acceptedDate'] }}.--}}
                    {{--                        </div>--}}
                    {{--                    @else--}}
                    {{--                        <a href="{{ route('obligation.update', ['version' => $dto['versionId'], 'accepted' => 1]) }}">--}}
                    {{--                            <button class="p-2 bg-green-500 text-white rounded-full">--}}
                    {{--                                I Accept These Obligations--}}
                    {{--                            </button>--}}
                    {{--                        </a>--}}
                    {{--                    @endif--}}
                </div>
            </div>
        </div>
    </div>

</div>
