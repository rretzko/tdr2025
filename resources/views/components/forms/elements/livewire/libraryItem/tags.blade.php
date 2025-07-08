<div class="border border-gray-400 p-2 mt-2">
    <div class="font-bold mb-2">
        Tags
        <span
            class="font-normal text-xs italic ">Add single words that you might use to search for this type of item.</span>
    </div>

    <div class="flex flex-col space-y-2">

        <div>
            <label>Current Tags</label>
            <div class="flex flex-row space-x-1">
                @forelse($form->tags AS $tag)
                    <button class="bg-blue-200 text-sm px-1 rounded-full">
                        {{ $tag }}
                    </button>
                @empty
                    <div class="text-sm italic">No pre-existing tags found</div>
                @endforelse
            </div>
        </div>
        {{-- New Tags --}}
        @if((! isset($form->policies['canEdit']['tags'])) || $form->policies['canEdit']['tags'])
            <textarea wire:model="tagCsv"
                      placeholder="Separate tags with a comma (ex:holiday, winter, seasonal)"></textarea>
        @endif

    </div>

</div>
