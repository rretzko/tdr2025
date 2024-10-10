@props([
    'active' => 1,
    'id' => 0,
    'livewire' => 0,
    'message' => 'Are you sure you want to remove this?',
])
<button
    @if($livewire) wire:click="deactivate({{ $id }})" @endif
type="button"
    @class([
        "text-white text-xs px-2 rounded-full hover:bg-gray-700",
        "bg-gray-600 hover:bg-gray-700" => $active,
        "bg-green-600 hover:bg-green-700" => (!$active),
        ])
    @if($livewire && $message) wire:confirm="{{ $message }}" @endif
>
    @if($active)
        Deactivate
    @else
        Reactivate
    @endif
</button>
