<form wire:submit="save" class="space-y-4  shadow-lg px-4 pb-4">

    <style>
        .narrow {
            width: 50%;
        }
    </style>
    {{-- SYSID, NAME --}}
    <fieldset class="flex flex-col space-y-2">
        <div class="flex flex-row space-x-2">
            <label class="flex items-center">Room</label>
            <div class="font-semibold">{{ $form->roomName ?: 'new' }}</div>
        </div>
    </fieldset>

    <fieldset class="flex flex-row space-y-2 items-center">
        <label for="form.judge2" class="text-sm w-24">Head Judge</label>
        <select wire:model="form.headJudge">
            <option value="0">- Select -</option>
            @foreach($members AS $key=>$member)
                <option value="{{ $key }}" wire:key="headJudge-{{ $key }}">
                    {{ $member }}
                </option>
            @endforeach
        </select>
    </fieldset>

    <fieldset class="flex flex-row space-y-2 items-center">
        <label for="form.judge2" class="text-sm w-24">Judge 2</label>
        <select wire:model="form.judge2">
            <option value="0">- Select -</option>
            @foreach($members AS $key=>$member)
                <option value="{{ $key }}" wire:key="judge2-{{ $key }}">
                    {{ $member }}
                </option>
            @endforeach
        </select>
    </fieldset>

    <fieldset class="flex flex-row space-y-2 items-center">
        <label for="form.judge3" class="text-sm w-24">Judge 3</label>
        <select wire:model="form.judge3">
            <option value="0">- Select -</option>
            @foreach($members AS $key=>$member)
                <option value="{{ $key }}" wire:key="judge3-{{ $key }}">
                    {{ $member }}
                </option>
            @endforeach
        </select>
    </fieldset>

    <fieldset class="flex flex-row space-y-2 items-center">
        <label for="form.judge4" class="text-sm w-24">Judge 4</label>
        <select wire:model="form.judge4">
            <option value="0">- Select -</option>
            @foreach($members AS $key=>$member)
                <option value="{{ $key }}" wire:key="judge4-{{ $key }}">
                    {{ $member }}
                </option>
            @endforeach
        </select>
    </fieldset>

    <fieldset class="flex flex-row space-y-2 items-center">
        <label for="form.judgeMonitor" class="text-sm w-24">Judge Monitor</label>
        <select wire:model="form.judgeMonitor">
            <option value="0">- Select -</option>
            @foreach($members AS $key=>$member)
                <option value="{{ $key }}" wire:key="judgeMonitor-{{ $key }}">
                    {{ $member }}
                </option>
            @endforeach
        </select>
    </fieldset>

    <fieldset class="flex flex-row space-y-2 items-center">
        <label for="form.monitor" class="text-sm w-24">Monitor</label>
        <select wire:model="form.monitor">
            <option value="0">- Select -</option>
            @foreach($members AS $key=>$member)
                <option value="{{ $key }}" wire:key="monitor2-{{ $key }}">
                    {{ $member }}
                </option>
            @endforeach
        </select>
    </fieldset>

    <fieldset class="flex flex-row space-x-2 pb-2 ">
        <button wire:click="saveJudge"
                class="bg-gray-800 text-white rounded-full px-2 text-xs"
                type="submit"
        >
            Save Judge(s)
        </button>

        <button wire:click="$toggle('showForm')"
                class="bg-gray-800 text-white rounded-full px-2 text-xs"
                type="button"
        >
            Close Form
        </button>
    </fieldset>

    {{-- SUCCESS INDICATOR --}}
    @if($showSuccessIndicator)
        <div class="text-green-600 italic text-xs">
            {{ $successMessage }}
        </div>
    @endif
</form>
