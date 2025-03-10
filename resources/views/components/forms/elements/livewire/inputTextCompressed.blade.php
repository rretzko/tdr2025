@props([
    'autofocus' => false,
    'blur' => true,
    'error' => '',
    'hint' => '',
    'label',
    'live' => false,
    'name',
    'placeholder' => '',
    'required' => false,
    'results' => '',
    'type' => 'text',
])
<div class="flex flex-col text-sm">
    <label for="{{ $name }}" class="text-left ml-2 @if($required) required @endif">
        {!! ucwords($label)  . ($required ? '<span class="text-sm text-red-600">*</span>' : '') !!}
    </label>
    <input type="{{ $type }}"
           @class([
            'w-1/8',
            'md:w-32',
            'text-sm',
            'border border-red-600' => $errors->has($name),
            ])
           @if($blur)wire:model.blur
           @elseif($live)wire:model.live.debounce.1000ms
           @else
               wire:model
           @endif ="{{ $name }}"
           placeholder="{{ $placeholder }}"
           @required($required)
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
            {!! $hint !!}
        </div>
    @endif

    {{-- RESULTS --}}
    <div class="flex flex-col"> {{--  bg-gray-100 w-1/3 mt-2 --}}
        {!! $results !!}
    </div>

    {{-- ERROR --}}
    @error($name)
    <x-input-error messages="{{ $message }}" aria-live="polite"/>
    @enderror

</div>
