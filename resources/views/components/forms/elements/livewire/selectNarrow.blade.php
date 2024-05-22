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
    <label for="county_id" class="required">{{ ucwords($label) }}</label>
    <select wire:model.live="{{ $name }}" class="narrow">
        @if($option0)
            <option value="0">- select -</option>
        @endif
        @foreach($options AS $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
    @error($name)
    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
    @enderror
    <div class="mt-2 text-sm text-blue-600">{!! $advisory !!}</div>
</div>
