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
    <div class="text-sm">
        @if(count($ensembleStudentMembers))
            iterate through ensembleStudentMembers array
        @else
            <div>No ensemble student members found.</div>
            <div>Student Members can be added from the <a href="/ensembles" class="text-blue-500">Ensembles</a> module.
            </div>
        @endif
    </div>
</div>
