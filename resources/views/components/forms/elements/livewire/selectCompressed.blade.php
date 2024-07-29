@props([
    'advisory' => '',
    'autofocus' => false,
    'disabled' => 0,
    'hint' => '',
    'label',
    'name',
    'option0' => false,
    'option0Label' => '- select -',
    'options',
    'placeholder' => '',
    'required' => false,
])
<div class="flex flex-col text-sm">
    <label for="{{ $name }}" class="text-left {{ $required }}">
        {!! ucwords($label)  . ($required ? '<span class="text-sm text-red-600">*</span>' : '') !!}
    </label>
    <select wire:model.live="{{ $name }}"
            @class([
                'w-1/8',
                'md:w-32',
                'text-sm',
                'border border-red-600' => $errors->has($name),
                ])
            aria-label="{{ $label }}"
            @error($name)
            aria-invalid="true"
            aria-description="{{ $message }}"
            @if($disabled) disabled @endif
        @enderror
    >
        @if($option0)
            <option value="0">{{ $option0Label }}</option>
        @endif
        @foreach($options AS $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>

    {{-- HINT --}}
    @if($hint)
        <div class="text-xs ml-1 italic">
            {{ $hint }}
        </div>
    @endif

    {{-- ERROR --}}
    @error($name)
    <x-input-error messages="{{ $message }}" aria-live="polite"/> @enderror

    {{-- ADVISORY --}}
    <div class="mt-2 text-sm text-blue-600">{!! $advisory !!}</div>

</div>
