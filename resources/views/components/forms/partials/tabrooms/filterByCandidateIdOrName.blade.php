<div class="w-full flex flex-col md:flex-row space-x-2">
    {{-- INPUT BOXES --}}
    <div class="">
        <div class="flex flex-col">
            <label>Candidate Id</label>
            <input type="text" wire:model.live.debounce.500ms="candidateId" class=""/>
            @error('candidateId')
            <div class="text-red-600 ml-2 mt-1 text-xs italic">{{ $candidateError }}</div>
            @enderror
        </div>
    </div>
    <div class="w-full">
        <div class="flex flex-col">
            <label>Candidate Last Name</label>
            <input type="text" wire:model.live.debounce.500ms="lastName" class="w-1/2"/>
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
            <div class="font-semibold">{{ $candidateVoicePartDescr }}</div>
        </div>
    @endif
    <div class="text-red-600 ml-2 mt-1 text-xs italic">
        {{ $candidateError }}
    </div>
</div>
