<div>

    {{-- HEADER --}}
    <div class="flex flex-row justify-between">
        <label>Candidates ({{ count($rows) }})</label>
        <div wire:click="$toggle('showAllButtons')" class="font-semibold text-green-600 cursor-pointer">
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
            <label class="flex bg-gray-200 w-full font-semibold mt-1 px-2 rounded-lg">{{ $button->descr }}</label>
            <div
                class="flex flex-row flex-wrap rounded-lg w-full text-black ml-2 space-x-2 space-y-1 items-center justify-start font-mono">
                @php $header = $button->abbr; @endphp
                @endif
                @if($button->abbr !== $header)
            </div>
            <label class="flex bg-gray-200 w-full font-semibold mt-1 px-2 rounded-lg">{{ $button->descr }}</label>
            <div
                class="flex flex-row flex-wrap rounded-lg w-full text-black ml-2 space-x-2 space-y-1 items-center justify-start font-mono">
                @php $header = $button->abbr; @endphp
                @endif
                <div class="flex flex-row">
                    <button wire:click=clickRef({{ $button->id }})
                            @class([
                                "w-fit border border-gray-600 rounded-lg px-2 text-xs sm:text-sm flex flex-row space-x-1",
                                "bg-green-500 text-white" => $button->status === 'completed',
                                "bg-red-600 text-yellow-400" => $button->status === 'errors',
                                "bg-black text-white" => $button->status === 'pending',
                                'bg-yellow-400 text-black' => $button->status === 'wip',
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
                        No candidates for adjudication found.
                    </div>
                @endforelse

            </div>

</div>
