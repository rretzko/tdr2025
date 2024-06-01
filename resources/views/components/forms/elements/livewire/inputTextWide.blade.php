@props([
    'autofocus' => false,
    'hint' => '',
    'label',
    'name',
    'placeholder' => '',
    'required' => false,
    'results' => '',
    'type' => 'text',
])
<div class="flex flex-col">
    <label for="{{ $name }}" class="@if($required) required @endif">{{ ucwords($label) }}</label>
    <input wire:model.blur="{{ $name }}"
           type="{{ $type }}"
           @class([
             'wide',
             'border border-red-600' => $errors->has($name),
             ])
           placeholder="{{ $placeholder }}"
           @if($autofocus) autofocus @endif
           aria-label="{{ $label }}"
           @error($name)
           aria-invalid="true"
           aria-description="{{ $message }}"
        @enderror
    />

    {{-- HINT --}}
    @if($hint)
        <div class="text-xs ml-1 italic">
            {{ $hint }}
        </div>
    @endif

    {{-- RESULTS --}}
    <div>{!! $results !!}</div>

    {{-- ERROR --}}
    @error($name)
    <x-input-error messages="{{ $message }}" aria-live="polite"/> @enderror
</div>
