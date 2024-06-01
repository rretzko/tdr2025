@props([
    'id' => 0,
    'livewire' => 0,
])
<button
    @if($livewire) wire:click="edit({{ $id }})" @endif
type="button"
    class="bg-indigo-600 text-white text-xs px-2 rounded-full hover:bg-indigo-700"
>
    Edit
</button>
