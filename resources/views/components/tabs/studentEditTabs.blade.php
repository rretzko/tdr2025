@props([
    'selectedTab' => 'bio',
    'tabs' => [],
])
<div>
    {{-- SMALL VIEWPORT DROP-DOWN --}}
    <div class="sm:hidden">
        <label for="tabs" class="sr-only">Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
        <select id="tabs" wire:model="selectedTab"
                class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            @foreach($tabs AS $tab)
                <option>{{ $tab }}</option>
            @endforeach
        </select>
    </div>

    {{-- LARGE VIEWPORT BUTTONS --}}
    <div class="hidden sm:block ">
        <div class="border border-gray-200 border-b-transparent shadow-lg">
            <nav class=" flex" aria-label="Tabs">

                @foreach($tabs AS $tab)
                    <button wire:click="$set('selectedTab', '{{ $tab }}')" wire:key="{{ $tab }}"
                        @class([
                         'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 w-1/4 border-b-2 py-4 px-1 text-center text-sm font-medium',
                         'border border-b-indigo-300 text-indigo-600' => ($selectedTab == $tab)
                     ])
                    >
                        {{ $tab }}
                    </button>
                @endforeach

            </nav>
        </div>
    </div>
</div>
