@props(['filters','methods'])

<div id="filters" class="bg-white p-2 mr-0.5 mt-1 rounded-lg space-y-2 h-full shadow-lg">
    <div class="flex justify-center">
        <x-heroicons.funnelSolidMicro/>
    </div>

    @forelse($methods AS $method)
        <h3 class="bold underline text-xs">{{ ucwords($method) }}</h3>
        @foreach($filters->$method() AS $key => $value)
            <label class="flex flex-row space-x-3 text-xs" wire:key="{{ $method }}-{{ $key }}">
                <input type="checkbox" wire:model.live="filters.{{ $method }}SelectedIds" value="{{ $key }}">
                <span>{{ $value }}</span>
            </label>
        @endforeach
    @empty
        <h3>No filters</h3>
    @endforelse

</div>
