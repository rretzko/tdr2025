<div class="space-y-4 mx-4 p-2 border border-gray-800 ">


    <div class="flex justify-end mr-8 p-2 text-blue-500">
        <button type="button" wire:click="clickPrinter">
            @include('components.heroicons.printer')
        </button>
    </div>

    @include('components.tables.scoringRosterPrivateTable')

</div>
