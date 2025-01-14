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

                {{-- OBLIGATIONS VERBIAGE --}}
                @if($obligationFile)
                    @include($obligationFile)
                @else
                    <div>{{ $content }}</div>
                @endif

                {{-- Accept/Reject buttons --}}
                <div class="m-4">
                    @if($obligationAccepted)
                        <div class="mb-2 text-green-500 italic text-sm">
                            Obligation accepted on: {{ $acceptedDate }}.
                        </div>

                        <button class="p-2 bg-red-500 text-white rounded-full"
                                type="button"
                                wire:click="rejectObligation()"
                        >
                            I Reject These Obligations
                        </button>
                    @elseif($schoolCountyName === 'Unknown')
                        <div class="text-red-500">
                            A valid school county is required to Accept these obligations. Please return to the
                            <a href="{{ route('schools') }}" class="underline font-semibold">Schools page</a> to edit
                            this value.
                        </div>
                    @else
                        <button type="button"
                                wire:click="acceptObligation()"
                                class="p-2 bg-green-500 text-white rounded-full"
                        >
                            I Accept These Obligations
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
