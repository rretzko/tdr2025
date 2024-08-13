@props([
    'advisory' => '',
    'autofocus' => false,
    'blur' => false,
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
<div class="flex flex-col">
    <label for="{{ $name }}" class="{{ $required }}">
        {!! ucwords($label) !!}
    </label>
    <select @if($blur) wire:model.blur @else
        wire:model.live
    @endif ="{{ $name }}"
            @class([
                'narrow',
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
