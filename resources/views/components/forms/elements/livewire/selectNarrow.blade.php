@props([
    'advisory' => 'advisory',
    'autofocus' => false,
    'label',
    'name',
    'option0' => false,
    'options',
    'placeholder' => '',
    'required',
])
<div class="flex flex-col">
    <label for="{{ $name }}" class="{{ $required }}">{{ ucwords($label) }}</label>
    <select wire:model.live="{{ $name }}"
            @class([
                'narrow',
                'border border-red-600' => $errors->has($name),
                ])
            aria-label="{{ $label }}"
            @error($name)
            aria-invalid="true"
            aria-description="{{ $message }}"
        @enderror
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
