@props([
    'autofocus' => false,
    'error' => '',
    'label',
    'name',
    'placeholder' => '',
    'required',
    'results' => '',
    'type' => 'text',
])
<div class="flex flex-col">
    <label for="{{ $name }}" class="@if($required) required @endif">{{ ucwords($label) }}</label>
    <input type="{{ $type }}"
           @class([
            'narrow',
            'border border-red-600' => $errors->has($name),
            ])
           wire:model.live="{{ $name }}"
           placeholder="{{ $placeholder }}"
           @if($autofocus) autofocus @endif
           aria-label="{{ $label }}"
           @error($name)
           aria-invalid="true"
           aria-description="{{ $message }}"
        @enderror
    />
    <div>{!! $results !!}</div>
    @error($name)
    <x-input-error messages="{{ $message }}" aria-live="polite"/> @enderror
</div>
