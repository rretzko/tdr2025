<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container" class="space-y-2">

        <div class="ml-2 p-2 rounded-lg shadow-lg flex flex-col">
            <h2 class="font-semibold">Filter by candidate id or last name</h2>

            {{-- INPUT BOXES --}}
            <div class="w-full flex flex-col md:flex-row space-x-2">
                <div class="">
                    <div class="flex flex-col">
                        <label>Candidate Id</label>
                        <input type="text" wire:model.live.debounce.500ms="candidateId" class=""/>
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
                @if($this->candidates->isNotEmpty())
                    collection found.
                @endif
            </div>

            {{-- FOUND RESULTS --}}
            <div class="flex flex-col">
                @if(strlen($candidateName))
                    <div class="flex flex-row">
                        <label class="w-24">Name: </label>
                        <div class="font-semibold">{{ $this->candidateName }}</div>
                    </div>
                    <div class="flex flex-row">
                        <label class="w-24">Teacher: </label>
                        <div class="font-semibold">{{ $this->candidateTeacher }}</div>
                    </div>
                    <div class="flex flex-row">
                        <label class="w-24">School: </label>
                        <div class="font-semibold">{{ $this->candidateSchool }}</div>
                    </div>
                    <div class="flex flex-row">
                        <label class="w-24 ">Voice Part: </label>
                        <div class="font-semibold">{{ $this->candidateVoicePartDescr }}</div>
                    </div>
                @endif
                <div class="text-red-600 ml-2 mt-1 text-xs italic">
                    {{ $candidateError }}
                </div>
            </div>
        </div>

        <div class="ml-2 p-2 border-gray-600 rounded-lg shadow-lg">
            <h2 class="font-semibold">Display scoring factors, judges table, tolerance.</h2>
        </div>

        <div class="ml-2 p-2 border-gray-600 rounded-lg shadow-lg">
            <h2 class="font-semibold">Form for updating selected candidate's score.</h2>
        </div>
    </div>{{-- END OF ID=CONTAINER --}}

</div>




