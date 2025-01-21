<div>

    {{-- HEADER --}}
    <div class="flex flex-row justify-between">
        <label>@if($showAllButtons)
                All
            @else
                Incomplete Scores
            @endif Candidates ({{ count($rows) }})</label>
        {{-- Hidden as of 20-Jan-25 due to doubts of its usefullness --}}
        <div wire:click="$toggle('showAllButtons')" class="font-semibold text-green-600 cursor-pointer hidden">
            @if($showAllButtons)
                Incomplete...
            @else
                All...
            @endif
        </div>
    </div>

    {{-- ADJUDICATION BUTTONS --}}
    @php
        $header = '';
    @endphp
    @forelse($rows AS $button)
        {{-- VOICE PART HEADER --}}
        @if(! $header)
            <label class="flex bg-gray-200 w-full font-semibold my-1 px-2 rounded-lg">{{ $button->descr }}</label>
            <div
                {{--                class="flex flex-row flex-wrap rounded-lg w-full text-black ml-2 space-x-2 space-y-1 items-center justify-start font-mono"--}}
                class="flex flex-wrap  rounded-lg w-full space-y-1 font-mono"
            >
                @php $header = $button->abbr; @endphp
                @endif
                @if($button->abbr !== $header)
            </div>
            <label class="flex bg-gray-200 w-full font-semibold my-1 px-2 rounded-lg">{{ $button->descr }}</label>
            <div
                {{--                class="flex flex-row flex-wrap rounded-lg w-full text-black ml-2 space-x-2 space-y-1 items-center justify-start font-mono line35"--}}
                class="flex flex-wrap  rounded-lg w-full space-y-1 font-mono"
            >
                @php $header = $button->abbr; @endphp
                @endif
                <div class="flex flex-row">
                    <button wire:click=clickRef({{ $button->id }})
                            @class([
                                "w-fit border border-gray-600 rounded-lg px-2 text-xs sm:text-sm flex flex-row space-x-1 mr-1",
                                "bg-green-500 text-white hover:bg-green-700" => $button->status === 'completed',
                                "bg-red-600 text-yellow-400 hover:bg-red-700" => $button->status === 'errors',
                                "bg-black text-white hover:bg-gray-600" => $button->status === 'pending',
                                'bg-yellow-400 text-black hover:bg-yellow-500' => $button->status === 'wip',
                            ])
                            title="{{ $button->status }}"
                    >
                        {{-- PREFIX: TOLERANCE --}}
                        <span @class([
                                "",
                                "text-red-600 items-center" => strlen($button->tolerance),
                            ])
                              title="@if(strlen($button->tolerance)) out of tolerance @endif"
                        >
                            {{ $button->tolerance }}
                        </span>

                        {{-- CANDIDATE REF ID --}}
                        {{ $button->ref }}

                        {{-- SUFFIX: SCORING COMPLETED FOR THIS JUDGE --}}
                        <span
                            @class([
                                "",
                                'text-black' => ($button->status === 'wip'),
                                'text-white' => (in_array($button->status, ['completed','errors','pending']))
                            ])
                        >
                            @if($button->scoringCompleted)
                                <span title="your work completed">
                                    <x-heroicons.check/>
                                </span>
                            @endif
                        </span>
                    </button>
                </div>
                @empty
                    <div class="text-black">
                        @if($showAllButtons)
                            No candidates for adjudication found.
                        @else
                            No candidates with incomplete scores found. Click the "All..." link to the right for ALL
                            candidates.
                        @endif
                    </div>
                @endforelse

            </div>

</div>
