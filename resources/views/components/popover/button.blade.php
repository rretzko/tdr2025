@props(['buttonId'])
<button id="{{ $buttonId }}" type="button" x-popover:button {{ $attributes }}>
    {{ $slot }}
</button>
