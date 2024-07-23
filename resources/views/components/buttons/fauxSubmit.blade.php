@props([
    'value' => 'submit',
    'wireClick' => 'process',
])
<div class="flex flex-col mt-2 max-w-xs">
    <label class="text-transparent">Submit</label>
    <button type="button" wire:click="{{ $wireClick }}"
            class="bg-gray-800 text-white px-2 rounded-full disabled:cursor-not-allowed disabled:opacity-50"
    >
        {{ ucwords($value) }}
    </button>
</div>
