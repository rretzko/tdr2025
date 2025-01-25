<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>


    <div class="flex flex-col md:flex-col md:columns-2">

        {{-- DATA FORM --}}
        <form wire:submit="save" class="my-4 mr-2 p-4 border border-gray-200 rounded-lg ">

            {{-- ENSEMBLES --}}
            <x-forms.elements.livewire.selectWide
                :autofocus="true"
                label="ensemble"
                name="form.ensembleId"
                :options="$ensembles"
            />

            {{-- SCHOOL YEAR --}}
            <x-forms.elements.livewire.selectWide
                label="senior year"
                name="srYear"
                :options="$schoolYears"
            />

            {{-- STUDENTS --}}
            <div class="flex flex-row justify-between mr-4">
                <label class="font-semibold underline">Eligible Students ({{ count($students) }}
                    ) {{ $this->srYear }}</label>
                <div>
                    <button type="button" wire:click="save()" class="bg-green-600 text-white px-2 rounded-lg shadow-lg">
                        Save New Members
                    </button>
                </div>
            </div>

            {{-- <div class="grid grid-flow-col grid-rows-auto sm:grid-rows-50"> --}}
            <div class="grid grid-flow-row sm:grid sm:grid-flow-col sm:grid-rows-50 md:grid-rows-25">
                @forelse($students AS $student)
                    <div class="flex flex-row space-x-1 items-center" wire:key="student_{{ $student['id'] }}">
                        <input type="checkbox" wire:model.live="form.newMembers" value="{{ $student['id'] }}"/>
                        <div>
                            {{ $student['last_name'] }}, {{ $student['first_name'] }} {{ $student['middle_name'] }}
                            (<span class="font-bold">{{ $student['calcGrade'] }}</span>/<span
                                class="text-xs">{{ $student['class_of'] }}</span>)
                        </div>
                    </div>
                @empty
                    <div class="col-span-2">no students found</div>
                @endforelse
            </div>
        </form>
    </div>
</div>
