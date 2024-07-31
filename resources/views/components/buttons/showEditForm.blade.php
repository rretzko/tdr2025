@props([
    'id',
])
<div>
    <button
        wire:click="$set('showEditForm', {{ $id }})"
        type="button"
        class="bg-indigo-600 text-white text-xs px-2 rounded-full hover:bg-indigo-700"
    >
        Edit
    </button>

</div>