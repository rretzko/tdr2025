<div
    class="ml-2 flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-4 sm:justify-center border border-white border-b-gray-200 text-sm pb-2">
    <div class="flex flex-row space-x-2 items-center">
        <input type="radio" wire:model.live="showEventEnsembleId" value="0"/>
        <div>All</div>
    </div>
    @foreach($eventEnsembles AS $ensemble)
        <div class="flex flex-row space-x-2 items-center">
            <input type="radio" wire:model.live="showEventEnsembleId" value="{{ $ensemble->id }}"/>
            <div>{{ $ensemble->ensemble_name }}</div>
        </div>
    @endforeach
</div>
