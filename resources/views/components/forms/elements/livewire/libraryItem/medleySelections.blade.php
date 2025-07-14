<div class="border border-gray-400 p-2 mt-2">
    <div class="font-bold mb-2">
        Medley Selections
        <span class="font-normal text-xs italic ">What songs are included in this medley?</span>
    </div>
    @if((! isset($form->policies['canEdit']['medleySelections'])) || $form->policies['canEdit']['medleySelections'])
        <div class="flex flex-col space-y-2">

            {{-- COMPOSER, ARRANGER, WAM, WORDS, MUSIC, LYRICIST, CHOREOGRAPHER --}}
            @for($i=1; $i<6; $i++)
                <div class="flex items-center">
                    <label class="w-20">Song {{ $i }}</label>
                    <input type="text" wire:model="form.medleySelections.{{ ($i - 1) }}"/>
                </div>
            @endfor

        </div>

    @else
        @for($i=1; $i<6; $i++)
            <div class="flex">
                <label class="w-12">Song {{ $i }}</label>
                <div>{{ $form->medleySelections[($i - 1)] }}</div>
            </div>
        @endfor
    @endif

</div>
