@props([
    'advisory' => '',
    'autofocus' => false,
    'disabled' => 0,
    'label',
    'name',
    'option0' => false,
    'options',
    'placeholder' => '',
    'required' => false,
])
<div class="flex flex-col">
    <label for="{{ $name }}" class="{{ $required }}">
        {{ ucwords($label) }}
    </label>
    <select wire:model.live="{{ $name }}"
            @class([
                'wide',
                'border border-red-600' => $errors->has($name),
                ])
            aria-label="{{ $label }}"
            @error($name)
            aria-invalid="true"
            aria-description="{{ $message }}"
            @enderror
            @if($disabled) disabled @endif
            @if($autofocus) autofocus @endif
    >
        @if($option0)
            <option value="0">- select -</option>
        @endif
        @foreach($options AS $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
    @error($name)
    <x-input-error messages="{{ $message }}" aria-live="polite"/> @enderror
    <div class="mt-2 text-sm text-blue-600">{!! $advisory !!}</div>
</div>
