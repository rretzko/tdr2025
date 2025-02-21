<div class="space-y-4 mx-4 p-2 border border-gray-800 ">

    @if($eventEnsembleCount > 1)
        @include('components.selectors.eventEnsembleSelectors')
    @endif

    <div class="flex justify-end mr-8 p-2 text-blue-500">

        <button type="button" wire:click="clickPrinter" class="flex flex-row space-x-2">
            <div class="{{ count($rows) < 401 ? 'hidden' : 'block' }}">
                Large pdf reports will be sent to your email address.
            </div>
            @include('components.heroicons.printer')
        </button>
    </div>

    @include('components.tables.scoringRosterPrivateTable')

</div>
