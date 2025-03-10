@props([
    'value' => 'submit and add another',
])
<div class="flex flex-col mt-2 max-w-xs">
    <button type="button" wire:click="saveAndStay"
            class="bg-gray-800 text-white px-2 rounded-full disabled:cursor-not-allowed disabled:opacity-50"
    >
        {{ ucwords($value) }}
    </button>
</div>
