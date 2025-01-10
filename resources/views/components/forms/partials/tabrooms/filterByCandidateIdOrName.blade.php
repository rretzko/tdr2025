<div class="w-full flex flex-col md:flex-row space-x-2">
    {{-- INPUT BOXES --}}
    <div class="">
        <div class="flex flex-col">
            <label>Candidate Id</label>
            <input type="text" wire:model.live.debounce.1000ms="candidateId" class="" autofocus/>
            @error('candidateId')
            <div class="text-red-600 ml-2 mt-1 text-xs italic">{{ $candidateError }}</div>
            @enderror
        </div>
    </div>
    <div class="w-full">
        <div class="flex flex-col">
            <label>Candidate Last Name</label>
            <input type="text" wire:model.live.debounce.1000ms="lastName" class="w-1/2"/>
        </div>
    </div>
</div>

{{-- COLLECTION OF NAME MATCHES --}}
<div>
    @if((! is_null($candidates)) && $candidates->isNotEmpty())
        <div class="flex flex-col justify-start space-y-1 mt-2 ml-2">
            @foreach($candidates AS $candidate)
                <button
                    type="button"
                    class="bg-gray-50 text-gray-800 text-sm border border-gray-600 px-2 rounded-lg w-fit"
                    wire:click="clickCandidateButton({{ $candidate->id }})"
                    wire:key="candidate_{{ $candidate->id }}"
                >
                    {{ $candidate->name }}
                </button>
            @endforeach
        </div>
    @endif
</div>

{{-- FOUND RESULTS --}}
<div class="flex flex-col mt-2">
    @if(strlen($candidateName))
        <div class="flex flex-row">
            <label class="w-24">Id: </label>
            <div class="font-semibold">{{ $candidateRef }}</div>
        </div>
        <div class="flex flex-row">
            <label class="w-24">Name: </label>
            <div class="font-semibold">{{ $candidateName }}</div>
        </div>
        <div class="flex flex-row">
            <label class="w-24">Teacher: </label>
            <div class="font-semibold">{{ $candidateTeacher }}</div>
        </div>
        <div class="flex flex-row">
            <label class="w-24">School: </label>
            <div class="font-semibold">{{ $candidateSchool }}</div>
        </div>
        <div class="flex flex-row">
            <label class="w-24 ">Voice Part: </label>
            <div>{{ $candidateVoicePartDescr }}</div>
        </div>

        <div class="flex flex-row border border-white border-t-gray-400 mt-2 py-2">
            <label class="w-24">Change voice part: </label>
            <div class="font-semibold">
                <select wire:model.live="selectedVoicePartId">
                    @foreach($eventVoiceParts AS $voicePart)
                        <option value="{{ $voicePart->id }}">
                            {{ $voicePart->descr }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center justify-center">
                <button wire:click="clickChangeVoicePartId()"
                        wire:confirm="Are you sure you want to remove ALL {{ $candidateScoreCount }} previously entered scores @if($hasRecordings) and any non-relevant recordings @endif ?"
                        class="bg-gray-200 border border-gray-800 ml-2 px-2 rounded-full shadow-lg"
                >
                    Change voice part
                </button>
            </div>

        </div>
        @if($candidateScoreCount)
            <div class="text-red-600 border border-white border-b-gray-400 pb-2">
                NOTE: Changing voice parts <u>after</u> auditions have started will remove
                <b>ALL</b> {{ $candidateScoreCount }} previously entered scores
                @if($hasRecordings)
                    and any non-relevant recordings
                @endif
                for this candidate.
            </div>
        @endif
    @endif
    <div class="text-red-600 ml-2 mt-1 text-xs italic">
        {{ $candidateError }}
    </div>
</div>
