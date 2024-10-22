<div>
    <label>Adjudication Form</label>
    {{-- HEADER --}}
    <div class="bg-gray-200 border border-gray-600 p-2 mb-2">
        <div>
            Id <span class="ml-2 font-semibold">{{ $form->ref }}</span>
        </div>
        <div>
            Room Tolerance: <span class="ml-2 font-semibold">{{ $form->roomTolerance }}</span>
        </div>
        <div>
            Best scores are always at the top of the drop-down menu.
        </div>
    </div>

    <form wire:submit="save">

        <fieldset id="one" class="w-full space-y-2 border border-gray-400 p-2 rounded-lg shadow-lg">
            @php $categoryDescr = ''; @endphp
            @foreach($form->factors AS $factor)
                @php
                    $start = $factor->best;
                    $end = $factor->worst;
                    $interval = $start > $end ? -$factor->interval_by : $factor->interval_by;
                @endphp

                @if($categoryDescr !== $factor['scoreCategory']->descr)
                    {{-- close the open fieldsets if exists --}}
                    @if(strlen($categoryDescr)) </fieldset>
        </fieldset> @endif
        {{-- update the $categoryDescr var --}}
        @php $categoryDescr = $factor['scoreCategory']->descr @endphp
        {{-- start a new fieldset --}}
        <fieldset id="two" class="flex flex-col w-full">
            {{-- set the category header label --}}
            <label class="font-semibold w-full px-2 bg-green-100">
                {{ ucwords($categoryDescr) }}
            </label>
            {{-- start the row of scoreable factors --}}
            <fieldset id="three" class="flex flex-row space-x-2 mb-2">
                @endif
                {{-- set each scoring factor in a dedicated fieldset --}}
                <fieldset id="four" class="flex flex-col">
                    <label for="{{ $form->scores[$factor->id] }}" class="text-center">
                        {{$factor->abbr}}
                    </label>
                    <select wire:model.live="form.scores.{{ $factor->id }}" class="w-fit"
                            wire:key="factor_{{ $factor->id }}">
                        @for($i=$start; (($start > $end) ? $i>=$end : $i<=$end ); $i=($i + $interval))
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </fieldset>
                @endforeach
            </fieldset>

            <div class="flex flex-row space-x-4 items-center">
                <div class="mt-4 justify-center">
                    Total: <span class="ml-2 font-semibold">{{ array_sum($form->scores) }}</span>
                </div>
                <input type="submit" wire:model="save"
                       class="bg-black text-white rounded-lg px-2 shadow-lg cursor-pointer"
                       value="Save"/>
            </div>

        </fieldset>

    </form>

</div>
