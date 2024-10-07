<div>

    {{-- HEADER --}}
    <div class="flex flex-row justify-between">
        <label>Audition Progress</label>
        <div wire:click="$toggle('showProgressBar')" class="font-semibold text-green-600 cursor-pointer">
            @if($showProgressBar)
                Hide...
            @else
                Show...
            @endif
        </div>
    </div>

    {{-- PROGRESS BAR --}}
    @if($showProgressBar)
        <div class="flex flex-row bg-gray-800 rounded-lg w-full text-yellow-400">
            <div class="bg-red-600 rounded-tl-lg rounded-bl-lg text-center" style="width: {{ $pctError }}%;">
                {{ $countError }}
            </div>
            <div class="bg-green-500 text-center text-white" style="width: {{ $pctCompleted }}%;">
                {{ $countCompleted }}
            </div>
            <div class="bg-yellow-400 text-center text-black" style="width: {{ $pctWip }}%;">
                {{ $countWip }}
            </div>
            <div class="bg-bla text-center text-white" style="width: {{ $pctPending }}%;">
                {{ $countPending }}
            </div>
        </div>
    @endif
</div>
