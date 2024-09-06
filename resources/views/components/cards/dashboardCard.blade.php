@props([
    'color' => 'indigo',
    'descr',
    'heroicon',
    'href',
    'label',
])
<div class="card w-3/4 md:w-1/4 md:min-h-40 border border-gray-500 p-2 rounded-lg mt-1 ">
    <a href="{{ $href }}" class="space-y-2">
        <div class="flex flex-row space-x-2 text-{{ $color }}-600">
            <div class=" mb-4">
                <x-dynamic-component :component="$heroicon"/>
            </div>
            <h2 class=" font-semibold">
                {{ ucwords($label) }}
            </h2></div>
        <div class="text-xs">
            {!! $descr !!}
        </div>
    </a>
</div>
