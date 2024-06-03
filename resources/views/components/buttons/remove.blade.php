@props([
    'id' => 0,
    'livewire' => 0,
    'message' => 'Are you sure you want to remove this?',
])
<button
    @if($livewire) wire:click="remove({{ $id }})" @endif
type="button"
    class="bg-red-600 text-white text-xs px-2 rounded-full hover:bg-red-700"
    @if($livewire && $message) wire:confirm="{{ $message }}" @endif
>
    Remove
</button>
