@props(['position' => 'bottom-center', 'buttonId'])

<div
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-transition.out.opacity
    x-on:toggle.window="if ($event.detail.id === '{{ $buttonId }}') open = !open"
    x-on:click.away="open = false"
    x-anchor.{{ $position }}.offset.1="document.getElementById('{{ $buttonId }}')"
    {{ $attributes->merge([
        'class' => 'absolute left-0 mt-2 bg-white rounded-md',
    ]) }}
>
    {{ $slot }}
</div>
