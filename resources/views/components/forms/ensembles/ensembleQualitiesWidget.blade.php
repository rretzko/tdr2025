<div class="border border-gray-700 p-2 mt-2">
    <h3 class="text-sm font-semibold">Ensemble Qualities</h3>

    {{-- VOICING --}}
    <div class="flex flex-row">

        {{-- MIXED --}}
        <div class="flex flex-row space-x-2 items-center">
            <label>
                <input type="radio" wire:model="form.voicing" wire:key="mixed" class="ml-2" value="mixed"/>
                <span>Mixed</span>
            </label>
        </div>

        {{-- TREBLE --}}
        <div class="flex flex-row space-x-2 items-center">
            <label>
                <input type="radio"  wire:model="form.voicing" wire:key="treble" class="ml-2" value="treble"/>
                <span>Treble</span>
            </label>
        </div>

        {{-- TTBB --}}
        <div class="flex flex-row space-x-2 items-center">
            <label>
                <input type="radio" wire:model="form.voicing" wire:key="ttbb" class="ml-2" value="ttdd"/>
                <span>ttbb</span>
            </label>
        </div>

    </div>

    {{-- A CAPPELLA CHECKBOX --}}
    <div>
        <div class="flex flex-row space-x-2 items-center">
            <label>
                <input type="checkbox" id="acappella" wire:model="form.acappella" wire:key="acappella" class="ml-2"/>
                <span>A Cappella</span>
            </label>
        </div>
    </div>

    {{-- JAZZ/SHOW/POP CHECKBOX --}}
    <div>
        <div class="flex flex-row space-x-2 items-center">
            <label>
                <input type="checkbox" id="jazz" wire:model="form.jazz" wire:key="jazz" class="ml-2"/>
                <span>Jazz/Show/Pop</span>
            </label>
        </div>
    </div>
</div>
