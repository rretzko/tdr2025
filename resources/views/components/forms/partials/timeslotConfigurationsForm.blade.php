<div id="configurations" class="space-y-2 my-2 border border-gray-600 p-2 rounded-lg">
    <fieldset class="space-x-2 w-full flex">
        <label for="startTime" class="w-1/6 flex items-center">Timeslots should start at</label>
        <input type="datetime-local" class="" wire:model.blur="startTime"/>
        @if($successStartTime)
            <x-save-fade message="{{ $successStartTime }}"/>
        @endif
    </fieldset>

    <fieldset class="space-x-2 w-full flex">
        <label for="endTime" class="w-1/6 flex items-center">Timeslots should end at</label>
        <div class="flex flex-col">
            <input type="datetime-local" class="" wire:model.blur="endTime"/>
            @if($successEndTime)
                <x-save-fade message="{{ $successEndTime }}"/>
            @endif
            @error('endTime')
            <x-input-error messages="{{ $message }}" aria-live="polite"/>
            @enderror
        </div>
    </fieldset>

    <fieldset class="space-x-2 flex">
        <label for="duration" class="flex items-center">Timeslots should be assigned in </label>
        <select wire:model.blur="duration">
            @for($i=1; $i<61; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <label class="flex items-center"> minute intervals.</label>
        @if($successDuration)
            <x-save-fade message="{{ $successDuration }}"/>
        @endif
    </fieldset>
    <div class="advisory w-2/3 text-xs border border-gray-600 rounded-lg p-2">
        Please note: Setting the start, end times, and interval should be done at the <b>beginning</b> of the timeslot
        assignment
        process. Changing the time settings <u>after</u> timeslots have been assigned will reset the values and
        require timeslot re-assignment for each school.
    </div>
</div>
