@props([
    'autofocus' => false,
    'cols' => 40,
    'hint' => '',
    'label',
    'name',
    'placeholder' => '',
    'required' => false,
    'results' => '',
    'rows' => 5,
    'suppressLabel' => false,
    'text' => '',
    'type' => 'text',
])
<div class="flex flex-col">

    <label for="{{ $name }}"
        @class([
            'w-full md:w-1/2',
            'required' => $required,
            'hidden' => $suppressLabel,
            'mt-1' => $suppressLabel
        ])
    >
        {{ ucwords($label) }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <textarea wire:model.blur="{{ $name }}"
              @class([
               'w-1/2',
                'border border-red-600' => $errors->has($name),
                'mt-1' => $suppressLabel,
                ])
              placeholder="{{ $placeholder }}"
              @if($autofocus) autofocus @endif
              @if($required) required @endif
              aria-label="{{ $label }}"
              @error($name)
              aria-invalid="true"
              aria-description="{{ $message }}"
              @enderror
    >
        {{ $text }}
    </textarea>

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
