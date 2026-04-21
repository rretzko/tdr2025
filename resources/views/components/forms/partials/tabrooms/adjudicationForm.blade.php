<div>
    <label>Adjudication Form for: <span class="text-lg font-semibold">{{ $form->ref }}</span></label>

    {{-- HEADER --}}
    <div class="bg-gray-200 border border-gray-600 p-2 mb-2">
        <div>
            Id <span class="ml-2 font-semibold">{{ $form->ref }}</span>
        </div>
        <div>
            {{ $room->room_name }} Room Tolerance: <span class="ml-2 font-semibold">{{ $form->roomTolerance }}</span>
        </div>
        <div>
            Best scores are always at the top of the drop-down menu.
        </div>
    </div>

    {{-- ADJUDICATION FORM --}}
    <form wire:submit="save">
        <fieldset class="w-full space-y-2 border border-gray-400 p-2 rounded-lg shadow-lg">

            <h3 class="font-semibold">
                Judge: {{ strtoupper($judge->user->name) }}
            </h3>

            @php
                $groupedFactors = $form->factors->groupBy(fn ($factor) => $factor->scoreCategory->descr);
            @endphp

            @foreach($groupedFactors as $categoryDescr => $factors)
                <fieldset class="flex flex-col w-full">
                    <label class="font-semibold w-full px-2 bg-green-100">
                        {{ ucwords($categoryDescr) }}
                    </label>
                    <fieldset class="flex flex-row space-x-2 mb-2">
                        @foreach($factors as $factor)
                            @php
                                $start = $factor->best;
                                $end = $factor->worst;
                                $interval = $start > $end ? -$factor->interval_by : $factor->interval_by;
                            @endphp
                            <fieldset class="flex flex-col">
                                <label class="text-center" for="factor_{{ $factor->id }}">
                                    {{ $factor->abbr }}
                                </label>
                                <select id="factor_{{ $factor->id }}"
                                        wire:model.live="form.scores.{{ $factor->id }}"
                                        wire:key="factor_{{ $factor->id }}"
                                        class="w-fit">
                                    @for($i = $start; (($start > $end) ? $i >= $end : $i <= $end); $i += $interval)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </fieldset>
                        @endforeach
                    </fieldset>
                </fieldset>
            @endforeach

            <div class="flex flex-row space-x-4 items-center">
                <div class="mt-4 justify-center">
                    Total: <span class="ml-2 font-semibold">{{ array_sum($form->scores) }}</span>
                </div>
                <input type="submit"
                       class="bg-black text-white rounded-lg px-2 shadow-lg cursor-pointer hover:bg-gray-300 hover:text-black focus:bg-gray-300 focus:text-black"
                       value="Save"/>
                <div class="bg-green-100 text-green-600 text-xs italic px-2 rounded-lg">
                    {{ $scoreUpdatedMssg }}
                </div>
            </div>

        </fieldset>
    </form>
</div>
