@props(['filters'])

<x-popover>

    <x-popover.button buttonId="filter-schools"
                      class="flex items-center gap-2 rounded-lg border pl-3 pr-2 py-1 text-gray-600 text-sm">
        <div class="flex flex-row space-x-2">
            <x-heroicons.funnelSolidMicro/>
            <div>Filter</div>
        </div>
        <x-heroicons.chevronDown/>
    </x-popover.button>

    <x-popover.panel buttonId="filter-schools"
                     class="border border-gray-100 shadow-xl z-10 w-64 overflow-hidden">
        <div class="flex flex-col divide-y divide-gray-100">
            @foreach ($filters->schools() as $school)
                <label class="flex items-center px-3 py-2 gap-2 cursor-pointer hover:bg-gray-100">
                    <input value="{{ $school->id }}" wire:model.live="filters.selectedSchoolIds" type="checkbox"
                           class="rounded border-gray-300">

                    <div class="text-sm text-gray-800">{{ $school->name }}</div>
                </label>
            @endforeach
        </div>
    </x-popover.panel>
</x-popover>
