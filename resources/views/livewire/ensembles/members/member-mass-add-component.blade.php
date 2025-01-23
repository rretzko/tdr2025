<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>


    <div class="flex flex-col md:flex-row">

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
            <label class="font-semibold underline">Eligible Students ({{ count($students) }})</label>
            @forelse($students AS $student)
                <div class="flex flex-row space-x-1 items-center" wire:key="student_{{ $student['id'] }}">
                    <input type="checkbox" value="{{ $student['id'] }}"/>
                    <div>{{ $student['last_name'] }}, {{ $student['first_name'] }} {{ $student['middle_name'] }}
                        ({{ $student['class_of'] }})
                    </div>
                </div>
            @empty
                no students found
            @endforelse
            <ul>
                <li>Students checkboxes
                    <ul>
                        <li>Name alpha (grade/class_of)</li>
                        <li>Remove any active members to ONLY show non-members</li>
                        <li>Add grade component to grade/class_of element</li>
                        <li>Add responsive formatting to fit non-members into columns across the page</li>
                    </ul>
                </li>
            </ul>
        </form>
    </div>
</div>
