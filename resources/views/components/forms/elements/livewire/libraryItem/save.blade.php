<div class="flex flex-row space-y-1 mt-1 space-x-1 ">
    <button
        type="button"
        class="bg-black text-yellow-300 rounded-lg px-2 mt-1 hover:text-white"
        wire:click="save()"
    >
        @if($form->sysId)
            Update
        @else
            Save
        @endif
    </button>
</div>
