@props([
    'label',
    'data',
    'wireModel' => '',
])
<div class="flex flex-row space-x-2">
    <label>{{ $label }}</label>
    {{--    <div class="data" wire:model="{{ $wireModel }}">{{ $data }}</div>--}}
    <input type="text" class="border border-transparent p-0 font-semibold" wire:model="{{ $wireModel }}" disabled/>
</div>