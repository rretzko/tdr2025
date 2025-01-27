@props([
    'id' => 0,
    'livewire' => 0,
    'message' => 'Are you sure you want to restore this?',
])
<button
    @if($livewire) wire:click="restore({{ $id }})" @endif
type="button"
    class="bg-yellow-400 text-black text-xs px-2 rounded-full hover:bg-yellow-500"
    @if($livewire && $message) wire:confirm="{{ $message }}" @endif
>
    Restore
</button>
