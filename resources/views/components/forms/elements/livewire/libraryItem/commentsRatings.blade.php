<div class="border border-gray-400 p-2 mt-2">
    <div class="flex flex-row mb-2 space-x-2">
        <div class="font-bold align-top min-w-52">Comments and Ratings</div>
        <div class="font-normal text-xs italic ">
            Add your comments and rating to provide reminders to your future self, help
            choir directors in the community, and save some money!
        </div>
    </div>
    <div class="">
        @if((! isset($form->policies['canEdit']['commentsRatings'])) || $form->policies['canEdit']['commentsRatings'])
            <div class="flex flex-col space-y-2 bg-white p-2 border border-gray-300 w-full">

                {{-- RATINGS --}}
                {{-- DISPLAY FORM FOR EXISTING ITEMS, NOT NEWLY CREATED ITEMS --}}
                @if($form->sysId)
                    @include('components.forms.elements.livewire.libraryItem.ratings')
                @endif

                {{-- LEVEL & DIFFICULTY --}}
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-x-2 sm:space-y-0">

                    {{-- LEVEL --}}
                    <div>
                        <label class="flex">Level</label>
                        <select wire:model="form.level">
                            @foreach(\App\Enums\Level::cases() AS $case)
                                <option value="{{ $case->value }}">{{ $case->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DIFFICULTY --}}
                    <div>
                        <label class="flex">Difficulty</label>
                        <select wire:model="form.difficulty">
                            @foreach(\App\Enums\Difficulty::cases() AS $case)
                                <option value="{{ $case->value }}">{{ $case->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>


                {{-- COMMENTS --}}
                <div class="flex flex-col">
                    <x-forms.elements.livewire.inputTextArea
                        label="comments"
                        name="form.comments"
                        placeholder="please add some comments here..."
                        required=true
                    />
                    {{--                    <textarea wire:model="form.comments" class="w-full" required placeholder="please add some comments here..." ></textarea>--}}
                </div>
            </div>
        @endif
    </div>

</div>
