<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">
        <style>
            .narrow {
                width: 20rem;
            }
        </style>

        {{-- PRINT OPTIONS --}}
        <div class="flex flex-col mt-4 p-2 border border-gray-300 rounded-lg shadow-lg">
            <label>Print by Registration ID, School, or Voice Part</label>
            <div class="flex flex-col sm:flex-row gap-2">

                {{-- INDIVIDUAL CANDIDATE --}}
                <div class="my-4 p-2 border border-gray-300 rounded-lg shadow-lg min-w-1/2 max-w-1/2">
                    <fieldset>
                        <x-forms.elements.livewire.inputTextCompressed
                            :blur=false
                            label="registration ID"
                            :live=true
                            name="candidateId"
                            placeholder="{{ $versionId }}####"
                        />
                    </fieldset>
                </div>

                {{-- SCHOOL CANDIDATES --}}
                <div class="my-4 p-2 border border-gray-300 rounded-lg shadow-lg min-w-1/2 max-w-1/2">
                    <fieldset>
                        <x-forms.elements.livewire.selectNarrow
                            label="school"
                            name="schoolId"
                            option0=true
                            :options="$schools"
                        />
                    </fieldset>
                </div>
            </div>

            {{-- VOICE PARTS CANDIDATES --}}
            <div class="my-2 px-2 ">
                <fieldset class="flex flex-col">
                    <label>Voice Parts</label>
                    <div class="flex flex-row flex-wrap gap-4 ">
                        @foreach($voiceParts AS $voicePart)
                            <button class="px-2 border border-gray-600 rounded-lg hover:bg-green-100"
                                    wire:click="clickVoicePart({{$voicePart->id}})"
                                    wire:key="voicePart_{{ $voicePart->id }}"
                            >
                                {{ $voicePart->descr }}
                            </button>
                        @endforeach
                    </div>
                </fieldset>
            </div>

            <div class="border border-white border-t-gray-300 mt-2 pt-2">
                <a href="{{ route('pdf.registrationCardAuditReport', ['version'=> $versionId]) }}" class="text-blue-500 hover:underline hover:text-blue-700" target="_blank">
                    Registration Card Audit Report
                </a>
            </div>
        </div>

    </div>{{-- END OF ID=CONTAINER --}}

</div>



