<div class="">
    <label for="form.title">
        <div>Voicing</div>
        @if((! isset($form->policies['canEdit']['voicing'])) || $form->policies['canEdit']['voicing'])
            <input
                type="text"
                wire:model.live.debounce="form.voicingDescr"
                class="w-5/12 sm:w-3/12"
                placeholder="SATB, TTB, etc."
            />
            @if(count($searchVoicings))
                <div class="ml-2 text-sm">
                    @foreach($searchVoicings AS $voicing)
                        <button class="text-blue-500"
                                type="button"
                                wire:click="clickVoicing({{ $voicing['id'] }})"
                                wire:key="voicing_{{ $voicing['id']}} "
                        >
                            {{ $voicing['descr'] }}
                        </button>
                    @endforeach
                </div>
            @endif
        @else
            <div class="border border-gray-600 w-11/12 sm:w-10/12 px-2">
                {{ $form->title }}
            </div>
        @endif
    </label>
</div>
