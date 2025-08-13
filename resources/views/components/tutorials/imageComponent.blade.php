@props([
    'alt',
    'id',
    'label',
    'url',
])
<div
    class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
    <div class="flex flex-col">
        <label>{{ $label }}</label>
        <div id="{{ $id }}">
            <img src="{{ Storage::disk('s3')->url( $url ) }}"
                 alt="{{ $alt }}">
        </div>
    </div>
</div>
