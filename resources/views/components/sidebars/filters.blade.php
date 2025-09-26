@props(['filters','methods','header' => 'none', 'hcEvents' => null])

<div id="filters" @class([
    "bg-white p-2 mr-0.5 mt-1 rounded-lg space-y-2 h-full shadow-lg",
    'min-w-24' => $header === 'programs',
])

>
    <div class="flex items-center justify-center space-x-1 w-full underline">
        <x-heroicons.funnelSolidMicro/>
        <span>Filters</span>
    </div>

    {{-- NJ ALL-STATE BUTTON --}}
    @if($header === 'programs')
        <button
            type="button"
            wire:click="njAllStatePrograms()"
            @class([
                "text-xs px-1 border rounded-lg shadow-lg",
                "bg-green-100 text-green-800 border-green-500" => is_null($hcEvents),
                "bg-red-100 text-red-800 border-red-500" => $hcEvents
                ])
        >
            NJ All-State
        </button>
    @endif

    @forelse($methods AS $method)
        <h3 class="bold underline text-xs">
            {{ ucwords($method) }}
        </h3>

        {{-- add aggregate filters --}}
        @if($method === 'classOfs')
            <label class="flex flex-row space-x-3 text-xs" wire:key="{{ $method }}-current">

                <input type="checkbox" wire:model.live="filters.{{ $method }}SelectedIds" value="current">
                <span>current</span>
            </label>

            <label class="flex flex-row space-x-3 text-xs" wire:key="{{ $method }}-alum">

                <input type="checkbox" wire:model.live="filters.{{ $method }}SelectedIds" value="alum">
                <span>alum</span>
            </label>
        @endif

        @foreach($filters->$method() AS $key => $value)

            <label class="flex flex-row space-x-3 text-xs" wire:key="{{ $method }}-{{ $key }}">

                <input type="checkbox"
                       @class([
                            '',
                            'bg-red-500' => in_array($value, ['prohibited','removed','withdrew']),
                            'checked:bg-red-500' => in_array($value, ['prohibited','removed','withdrew']),
                            'checked:border-red-500' => in_array($value, ['prohibited','removed','withdrew']),
                            'bg-gray-500' => ($value === 'eligible'),
                            'checked:bg-gray-500' => ($value === 'eligible'),
                            'checked:border-gray-500' => ($value === 'eligible'),
                            'bg-green-500' => ($value === 'registered'),
                            'checked:bg-green-500' => ($value === 'registered'),
                            'checked:border-green-500' => ($value === 'registered'),
                            'bg-yellow-300' => ($value === 'engaged'),
                            'checked:bg-yellow-300' => ($value === 'engaged'),
                            'checked:border-yellow-300' => ($value === 'engaged'),
                        ])
                       wire:model.live="filters.{{ $method }}SelectedIds"
                       value="{{ $key }}"
                >
                <span>{{ $value }}</span>
            </label>

        @endforeach
    @empty
        <h3>No filters</h3>
    @endforelse

</div>
