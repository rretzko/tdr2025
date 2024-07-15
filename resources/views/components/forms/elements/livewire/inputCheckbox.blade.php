@props([
    'blur' => 'true',
    'error' => '',
    'hint' => '',
    'key' => false,
    'label',
    'name',
    'placeholder' => '',
    'required' => false,
    'type' => 'text',
    'value' => '1',
])
<div class="flex flex-row mt-4 space-x-2">
    <input type="checkbox"
           value="{{ $value }}"

           @class([
            'mt-0.5 rounded',
            'border border-red-600' => $errors->has($name),
            ])

           @if($blur === 'true')
               wire:model.blur="{{ $name }}"
           @else
               wire:model="{{ $name }}"
           @endif
           aria-label="{{ $label }}"

           @if($key)
               wire:key='{{ $key }}'
           @endif

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
