<div class=" w-1/2 p-2 bg-blue-100 rounded-lg">
    <div class="flex flex-row justify-between mr-4 mb-2">
        <header class="">
            {{ $ensembleName }} {{ $program->school_year }} Student Membership ({{ count($ensembleStudentMembers) }})
        </header>
        <button wire:click="hideEnsembleStudentRoster()" class="text-red-500">
            Hide...
        </button>
    </div>

    {{-- MEMBERSHIP ROSTER --}}
    <div class="flex flex-col  text-sm mb-2 ml-4">
        @forelse($ensembleStudentMembers AS $student)
            <button wire:click="removeEnsembleMember({{ $student['id'] }})"
                    wire:confirm="Are you sure you want to remove {{ $student['name'] }} from {{ $ensembleName }} {{ $program->school_year }} membership?"
                    class="hover:text-red-500 text-left"
                    title="click to remove {{ $student['name'] }} from {{ $ensembleName }} {{ $program->school_year }} membership"
            >
                {{ $student['studentData'] }}
            </button>
        @empty
            <div>No ensemble student members found.</div>
        @endforelse
    </div>

    {{-- ADD NEW MEMBER OPTIONS --}}
    <div class="bg-blue-200 border border-blue-300 rounded-lg p-2 shadow-lg">
        Student ensemble members can be added through the
        <a href="/ensembles/members/new" class="text-blue-500">
            Ensembles
        </a>
        application
    </div>
    {{--
    <div class="bg-blue-200 border border-blue-300 rounded-lg p-2 shadow-lg">
        <div>Individual student members can be added by clicking
            <button wire:click="addOneStudent()"
                    class="text-blue-500">
                here,
            </button>
        </div>
        <div>or you can upload multiple students by clicking
            <button wire:click="uploadStudents"
                    class="text-blue-500">
                here.
            </button>
        </div>
    </div>

    --}}

</div>
