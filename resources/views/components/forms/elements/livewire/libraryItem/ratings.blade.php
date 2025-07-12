<div>
    <label class="mb-2">Rating (@json($form->rating))</label>
    <div class="flex flex-row w-full justify-around border border-gray-300 p-2">
        <div class="flex flex-col justify-center " wire:click="$set('form.rating','1')">
            <label class="text-center text-xs">Once &<br/>done</label>
            <div @class([
"flex items-center justify-center hover:text-blue-400",
'text-green-500' => ($form->rating == 1),
])
            >
                @if($form->rating == 1)
                    <x-heroicons.starSolid/>
                @else
                    <x-heroicons.star/>
                @endif
            </div>
            <div class="text-xs text-center">
                1
            </div>
        </div>
        <div class="flex flex-col justify-center" wire:click="$set('form.rating','2')">
            <label class="text-center text-xs"><br/>Meh</label>
            <div @class([
"flex items-center justify-center hover:text-blue-400",
'text-green-500' => ($form->rating == 2),
])
            >
                @if($form->rating == 2)
                    <x-heroicons.starSolid/>
                @else
                    <x-heroicons.star/>
                @endif
            </div>
            <div class="text-xs text-center">
                2
            </div>
        </div>
        <div class="flex flex-col justify-center " wire:click="$set('form.rating','3')">
            <label class="text-center text-xs"><br/>Good</label>
            <div @class([
"flex items-center justify-center hover:text-blue-400",
'text-green-500' => ($form->rating == 3),
])
            >
                @if($form->rating == 3)
                    <x-heroicons.starSolid/>
                @else
                    <x-heroicons.star/>
                @endif
            </div>
            <div class="text-xs text-center">
                3
            </div>
        </div>
        <div class="flex flex-col justify-center " wire:click="$set('form.rating','4')">
            <label class="text-center text-xs"><br/>Showstopper!</label>
            <div @class([
"flex items-center justify-center hover:text-blue-400",
'text-green-500' => ($form->rating == 4),
])
            >
                @if($form->rating == 4)
                    <x-heroicons.sparkleSolid/>
                @else
                    <x-heroicons.sparkle/>
                @endif
            </div>
            <div class="text-xs text-center">
                4
            </div>
        </div>
        <div class="flex flex-col justify-center " wire:click="$set('form.rating','5')">
            <label class="text-center text-xs">Every student<br/>should sing this!</label>
            <div @class([
"flex items-center justify-center hover:text-blue-400",
'text-green-500' => ($form->rating == 5),
])
            >
                @if($form->rating == 5)
                    <x-heroicons.sparkleSolid/>
                @else
                    <x-heroicons.sparkle/>
                @endif
            </div>
            <div class="text-xs text-center">
                5
            </div>
        </div>
    </div>
</div>
