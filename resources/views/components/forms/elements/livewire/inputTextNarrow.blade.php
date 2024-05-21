@props([
    'label',
    'name',
    'required',
    'results',
])
<div class="flex flex-col">
    <label for="{{ $name }}" class="@if($required) required @endif">{{ ucwords($label) }}</label>
    <input type="text" class="narrow " wire:model.live="{{ $name }}"/>
    <div>{!! $results !!}</div>
    @error('{{ $name }}')
    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
    @enderror
</div>
