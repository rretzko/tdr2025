<div>
    <label>Adjudication Form</label>
    <form wire:submit="save">
        <div>
            Sys.Id <span class="ml-2 font-semibold">{{ $form->ref }}</span>
        </div>
        <div>
            Room Tolerance: <span class="ml-2 font-semibold">{{ $form->roomTolerance }}</span>
        </div>
        <div>
            Best scores are always at the top of the drop-down menu.
        </div>
        <div class="w-full space-y-2 border border-gray-400 p-2 rounded-lg shadow-lg">
            @foreach($form->factors AS $factor)
                @php
                    $start = $factor->best;
                    $end = $factor->worst;
                    $interval = $start > $end ? -$factor->interval_by : $factor->interval_by;
                @endphp
                <fieldset class="flex flex-row w-full">
                    <label for="{{ $form->scores[$factor->id] }}" class="w-1/6">{{$factor->factor}}</label>
                    <select wire:model.live="form.scores.{{ $factor->id }}" wire:key="factor_{{ $factor->id }}">
                        @for($i=$start; (($start > $end) ? $i>=$end : $i<=$end ); $i=($i + $interval))
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </fieldset>
            @endforeach
            <div>
                Total: <span class="ml-2 font-semibold">{{ array_sum($form->scores) }}</span>
            </div>
        </div>
    </form>

</div>
