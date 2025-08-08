<div class="border border-gray-400 p-2 mt-2">
    <div class="font-bold mb-2">
        {{ ucwords($form->itemType) }} Selections
        <span class="font-normal text-xs italic ">What songs are included in this medley?</span>
    </div>
    @if((! isset($form->policies['canEdit']['medleySelections'])) || $form->policies['canEdit']['medleySelections'])
        <div class="flex flex-col space-y-2">

            {{-- COMPOSER, ARRANGER, WAM, WORDS, MUSIC, LYRICIST, CHOREOGRAPHER --}}
            @for($i=0; $i<=count($form->medleySelections); $i++)
                <div class="flex items-center">
                    <label class="w-1/12">Song {{ ($i + 1) }}</label>
                    <input type="text"
                           wire:model.blur="form.medleySelections.{{ $i }}"
                           class="w-5/6"
                           wire:key="selection_{{ $i }}"
                           id="selection_{{ $i }}"
                    />
                </div>
            @endfor
            {{-- Add Initial input --}}
            {{--            @if(! count($form->medleySelections))--}}
            {{--                <div class="flex">--}}
            {{--                    <label class="w-1/12">Song {{ (count($form->medleySelections) + 1) }}</label>--}}
            {{--                    <input type="text"--}}
            {{--                           wire:model.live.blur="form.medleySelections.{{ (count($form->medleySelections)) }}"--}}
            {{--                           class="w-5/6"--}}
            {{--                           wire:key="newInput_{{ count($form->medleySelections) }}"--}}
            {{--                           id="newInput_{{ count($form->medleySelections) }}"--}}
            {{--                    />--}}
            {{--                </div>--}}
            {{--            @endif--}}

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
