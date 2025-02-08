@props([
    'type' => 'submit',
    'value' => 'submit',
    'livewire' => false,
    'wireClick' => '',
])
<div class="flex flex-col mt-2 max-w-xs">
    <button type="{{ $type }}"
            class="bg-gray-800 text-white px-2 rounded-full disabled:cursor-not-allowed disabled:opacity-50"
            @if($livewire) wire:click="{{ $wireClick }}" @endif
    >
        {{ ucwords($value) }}
    </button>
</div>
