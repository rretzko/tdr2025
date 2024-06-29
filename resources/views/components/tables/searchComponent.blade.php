<div class="flex flex-row justify-start border border-gray-600 pl-1">
    <div class="flex justify-center items-center ">
        <x-heroicons.magnifyingGlass class=""/>
    </div>
    <input wire:model.live.debounce="search" class="border border-transparent focus:border-transparent"
           type="text" placeholder="Search"/>
</div>
