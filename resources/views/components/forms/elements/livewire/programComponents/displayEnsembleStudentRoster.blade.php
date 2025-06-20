<div class=" w-1/2">
    <div class="flex flex-row justify-between mr-4 mb-2">
        <header class="font-semibold">
            Ensemble Student Membership
        </header>
        <button wire:click="hideEnsembleStudentRoster()" class="text-red-500">
            Hide...
        </button>
    </div>

    {{-- MEMBERSHIP ROSTER --}}
    <div class="text-sm mb-2">
        @if(count($ensembleStudentMembers))
            iterate through ensembleStudentMembers array
        @else
            <div>No ensemble student members found.</div>
        @endif
    </div>

    {{-- ADD NEW MEMBER OPTIONS --}}
    <div class="bg-gray-100 border border-gray-300 rounded-lg p-2 shadow-lg">
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

</div>
