@props([
    'error' => '',
    'hint' => '',
    'label',
    'name',
    'placeholder' => '',
    'required' => false,
    'type' => 'text',
])
<div class="flex flex-row mt-4 space-x-2">
    <input type="checkbox"
           @class([
            'mt-0.5',
            'border border-red-600' => $errors->has($name),
            ])
           wire:model.blur="{{ $name }}"
           aria-label="{{ $label }}"
           @error($name)
           aria-invalid="true"
           aria-description="{{ $message }}"
        @enderror
    />
    <label for="{{ $name }}" class="@if($required) required @endif">{{ ucwords($label) }}</label>

    {{-- HINT --}}
    @if($hint)
        <div class="text-xs ml-1 italic">
            {{ $hint }}
        </div>
    @endif

    {{-- ERROR --}}
    @error($name)
    <x-input-error messages="{{ $message }}" aria-live="polite"/>
    @enderror

</div>
