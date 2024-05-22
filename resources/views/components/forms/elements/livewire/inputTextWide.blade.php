@props([
    'autofocus' => false,
    'label',
    'name',
    'placeholder' => '',
    'required',
    'results' => '',
])
<div class="flex flex-col">
    <label for="{{ $name }}" class="@if($required) required @endif">{{ ucwords($label) }}</label>
    <input wire:model.live="{{ $name }}"
           type="text"
           class="wide"
           placeholder="{{ $placeholder }}"
           @if($autofocus) autofocus @endif
    />
    <div>{!! $results !!}</div>
    @error('{{ $name }}')
    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
    @enderror
</div>
