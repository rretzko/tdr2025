<div class="space-y-4 mx-4 p-2 border border-gray-200 ">

    @if($eventEnsembleCount > 1)
        @include('components.selectrors.eventEnsembleSelectors')
    @endif

    @if($displayReportData === 'byVoicePart')
        <div class="mx-4 flex flex-col md:flex-row md:space-x-4">
            @foreach($voiceParts AS $voicePart)
                <div class="items-center w-fit md:w-full">
                    <input type="radio" wire:model.live="voicePartId" value="{{ $voicePart->id }}">
                    <label>{{ $voicePart->descr }}</label>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex justify-end mr-8 border border-white border-t-gray-200 p-2 text-blue-500">
        <button type="button" wire:click="clickPrinter">
            @include('components.heroicons.printer')
        </button>
    </div>

    @include('components.tables.scoringRosterTable')

</div>
