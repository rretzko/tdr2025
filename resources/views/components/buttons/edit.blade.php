@props([
    'disabled' => false,
    'id',
    'livewire' => 0,
    'route' => '',
])
<div>
    @if($route)
        <a href="{{ route($route, $id) }}">
            @endif
            <button
                @if($livewire) wire:click="edit({{ $id }})" @endif
            type="button"
                class="bg-indigo-600 text-white text-xs px-2 rounded-full hover:bg-indigo-700"
                @disabled($disabled)
            >
                Edit
            </button>
            @if($route)
        </a>
    @endif
</div>
