<div class="px-4">
    <h2>Add {{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            {{-- SYS ID --}}
            <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" data="new"/>

            <fieldset id="user-info">

                <fieldset class="flex flex-col md:flex-row space-x-2" id="name-parts">

                    <x-forms.elements.livewire.inputTextNarrow
                        label="first"
                        name="form.first"
                        required
                        autofocus
                    />

                    <x-forms.elements.livewire.inputTextNarrow
                        label="middle"
                        name="form.middle"
                    />

                    <x-forms.elements.livewire.inputTextNarrow
                        label="last"
                        name="form.last"
                        required
                    />

                    <x-forms.elements.livewire.inputTextNarrow
                        label="suffix"
                        name="form.suffix"
                        hint="Jr., III, etc."
                    />

                </fieldset>

                <x-forms.elements.livewire.inputTextWide
                    label="email"
                    name="form.email"
                    required
                    hint="Email used by the student to log into StudentFolder.info"
                />

            </fieldset>

            <fieldset id="student-info">

                <x-forms.elements.livewire.selectWide
                    label="school"
                    name="schoolId"
                    option0
                    :options="$schools"
                    required="required"
                />

                <x-forms.elements.livewire.selectNarrow
                    label="grade"
                    name="form.grade"
                    option0
                    :options="$grades"
                    required="required"
                />

            </fieldset>


            {{--            --}}{{-- GRADES TAUGHT IN SCHOOL --}}
            {{--            <div class="flex flex-col">--}}
            {{--                <label for="" class="required">Grades Taught in School</label>--}}
            {{--                <div--}}
            {{--                    @class([--}}
            {{--                     'flex flex-row space-x-2',--}}
            {{--                     'border border-red-600 px-2 py-1' => $errors->has('form.gradesTaught'),--}}
            {{--                     ])--}}
            {{--                    aria-label="grades taught"--}}
            {{--                    @error('form.gradesTaught')--}}
            {{--                    aria-invalid="true"--}}
            {{--                    aria-description="{{ $message }}"--}}
            {{--                    @enderror--}}
            {{--                >--}}
            {{--                    @for($i=1; $i<13; $i++)--}}
            {{--                        <div>--}}
            {{--                            <input wire:model.blur="form.gradesTaught"--}}
            {{--                                   wire:key="gradesTaught{{ $i }}"--}}
            {{--                                   type="checkbox"--}}
            {{--                                   value="{{ $i }}"--}}
            {{--                                   aria-label="grade {{ $i }}"--}}
            {{--                            />--}}
            {{--                            <label>{{ $i }}</label>--}}
            {{--                        </div>--}}
            {{--                    @endfor--}}
            {{--                </div>--}}
            {{--                @error('form.gradesTaught')--}}
            {{--                <x-input-error messages="{{ $message }}" aria-live="polite"/>--}}
            {{--                @enderror--}}

            {{--            </div>--}}

            {{--            --}}{{-- GRADES I TEACH IN SCHOOL --}}
            {{--            <div class="flex flex-col">--}}
            {{--                <label for="" class="required">Grades I Teach in School</label>--}}
            {{--                <div--}}
            {{--                    @class([--}}
            {{--                     'flex flex-row space-x-2',--}}
            {{--                     'border border-red-600 px-2 py-1' => $errors->has('form.gradesITeach'),--}}
            {{--                     ])--}}
            {{--                    aria-label="grades i teach"--}}
            {{--                    @error('form.gradesITeach')--}}
            {{--                    aria-invalid="true"--}}
            {{--                    aria-description="{{ $message }}"--}}
            {{--                    @enderror--}}
            {{--                >--}}
            {{--                    @for($i=1; $i<13; $i++)--}}
            {{--                        <div>--}}
            {{--                            <input wire:model.blur="form.gradesITeach"--}}
            {{--                                   wire:key="gradesITeach{{ $i }}"--}}
            {{--                                   type="checkbox"--}}
            {{--                                   value="{{ $i }}"--}}
            {{--                                   aria-label="grade {{ $i }}"--}}
            {{--                            />--}}
            {{--                            <label>{{ $i }}</label>--}}
            {{--                        </div>--}}
            {{--                    @endfor--}}
            {{--                </div>--}}
            {{--                @error('form.gradesITeach')--}}
            {{--                <x-input-error messages="{{ $message }}" aria-live="polite"/>--}}
            {{--                @enderror--}}

            {{--            </div>--}}

            {{--            --}}{{-- WORK EMAIL --}}
            {{--            <div class="flex flex-col">--}}
            {{--                <x-forms.elements.livewire.inputTextWide label="work email"--}}
            {{--                                                         name="form.email"--}}
            {{--                                                         required--}}
            {{--                                                         type="email"--}}
            {{--                />--}}
            {{--                @if($emailVerified)--}}
            {{--                    <div class="mt-2 text-sm text-green-600">Verified</div>--}}
            {{--                @else--}}
            {{--                    <div class="mt-2 text-sm text-red-600">Unverified</div>--}}
            {{--                @endif--}}
            {{--            </div>--}}

            {{--        </div>--}}

            {{-- SUBMIT --}}
            <div class="flex flex-col mt-2 max-w-xs">
                <button type="submit"
                        class="bg-gray-800 text-white px-2 rounded-full disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Submit
                </button>
            </div>

            {{-- SUCCESS INDICATOR --}}
            <x-forms.indicators.successIndicator :showSuccessIndicator="$showSuccessIndicator"
                                                 message="{{  $successMessage }}"/>

    </form>
</div>

