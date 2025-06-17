<div>
    <header class="flex flex-col mb-2">
        <label>Addendums</label>
        <hint class="text-xs italic">
            Optional verbiage (ex. soloist names, accompanying instrumentalists, etc.)
        </hint>
    </header>

    <div>
        <label for="form.addendum1" class="flex flex-col">Addendum 1
            <input type="text" wire:model="form.addendum1"/>
        </label>
    </div>

    <div>
        <label for="form.addendum2" class="flex flex-col">Addendum 2
            <input type="text" wire:model="form.addendum2"/>
        </label>
    </div>

    <div>
        <label for="form.addendum3" class="flex flex-col">Addendum 3
            <input type="text" wire:model="form.addendum3"/>
        </label>
    </div>

</div>{{-- end of id=addendums --}}
