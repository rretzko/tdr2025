@props(['buttonId'])

<button id="{{ $buttonId }}" type="button" x-data
        x-on:click="$dispatch('toggle', { id: '{{ $buttonId }}' })" {{ $attributes }}>
    {{ $slot }}
</button>
