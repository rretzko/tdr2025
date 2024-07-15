@props([
    'showErrorIndicator' => false,
    'message' => '',
])
<div
    x-show="$wire.showErrorIndicator"
    x-transition.out.opacity.duration.2000ms
    x-effect="if($wire.showErrorIndicator) setTimeout(() => $wire.showErrorIndicator = false, 3000)"
    class="flex justify-start pt-4"
    aria-live="polite"
>
    <div class="flex flex-col gap-2 items-start text-red-500 text-sm font-medium">
        <div class="flex flex-row space-x-2">
            <x-heroicons.xInCircle/>
            <div>Errors</div>
        </div>
        {!! $message !!}
    </div>
</div>

