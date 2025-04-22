@props([
    'click' => 'pending',
    'testFactor',
    'buttonLabel',
    'legendLabel'
])
<div class="flex flex-row space-x-2 items-center">
    <button
        wire:click="{{ $click }}()"
        @class([
                        "flex w-fit min-w-1/4 px-2 rounded-lg shadow-lg",
                        "bg-gray-300" => $testFactor,
                        "bg-yellow-300" => (!$testFactor)
                    ])
        @disabled($testFactor)
    >
        {{ $buttonLabel }}
    </button>
    <div class="text-sm italic @if($testFactor) block @else hidden @endif">
        {{ $legendLabel }} = {{ $testFactor }}
    </div>
</div>
