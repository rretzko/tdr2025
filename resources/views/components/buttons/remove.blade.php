@props([
    'id' => 0,
    'livewire' => 0,
])
<button
    @if($livewire) wire:click="remove({{ $id }})" @endif
type="button"
    class="bg-red-600 text-white text-xs px-2 rounded-full hover:bg-red-700"
>
    Remove
</button>
