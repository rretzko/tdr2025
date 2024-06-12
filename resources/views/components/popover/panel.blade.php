@props(['position' => 'bottom-center', 'buttonId'])

<div
    x-popover:panel
    x-cloak
    x-transition.out.opacity
    x-anchor.{{ $position }}.offset.1="document.getElementById('{{ $buttonId }}')"
    {{ $attributes->merge([
        'class' => 'absolute left-0 mt-2 bg-white rounded-md',
    ]) }}
>
    {{ $slot }}
</div>
