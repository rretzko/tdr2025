<div class="px-4">
    <h2>{{ ucwords($header) }} {{ $form->name }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

        <div class="space-y-4">
            <x-forms.styles.genericStyle/>

            {{-- SYS ID --}}
            <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" :data="$form->sysId" wireModel="form.sysId"/>

            {{-- POSTAL CODE --}}
            <x-forms.elements.livewire.inputTextNarrow label="zip code"
                                                       name="form.postalCode"
                                                       required
                                                       autofocus
                                                       :results="$form->resultsPostalCode"
            />

            {{-- SCHOOL NAME --}}
            <x-forms.elements.livewire.inputTextWide label="school name"
                                                     name="form.name"
                                                     placeholder="Enter full name without abbreviations"
                                                     required
                                                     :results="$form->resultsName"
            />

            {{-- ABBREVIATION --}}
            <x-forms.elements.livewire.inputTextNarrow label="abbreviation"
                                                       name="form.abbr"
                                                       required
                                                       hint="One to six characters..."
            />

            {{-- CITY --}}
            <x-forms.elements.livewire.inputTextWide label="city"
                                                     name="form.city"
                                                     placeholder=""
                                                     required
                                                     :results="$form->resultsCity"
            />

            {{-- COUNTY --}}
            <x-forms.elements.livewire.selectNarrow advisory="{{  $form->advisoryCountyId }}"
                                                    label="county"
                                                    name="form.countyId"
                                                    option0
                                                    :options="$counties"
                                                    required="required"

            />

            {{-- GRADES TAUGHT IN SCHOOL --}}
            <div class="flex flex-col">
                <label for="" class="required">Grades Taught in School</label>
                <div
                    @class([
                     'flex flex-row space-x-2',
                     'border border-red-600 px-2 py-1' => $errors->has('form.gradesTaught'),
                     ])
                    aria-label="grades taught"
                    @error('form.gradesTaught')
                    aria-invalid="true"
                    aria-description="{{ $message }}"
                    @enderror
                >
                    @for($i=1; $i<13; $i++)
                        <div>
                            <input wire:model.blur="form.gradesTaught"
                                   wire:key="gradesTaught-{{ $i }}"
                                   type="checkbox"
                                   value="{{ $i }}"
                                   aria-label="grade {{ $i }}"
                            />
                            <label>{{ $i }}</label>
                        </div>
                    @endfor
                </div>
                @error('form.gradesTaught')
                <x-input-error messages="{{ $message }}" aria-live="polite"/>
                @enderror

            </div>

            {{-- GRADES I TEACH IN SCHOOL --}}
            <div class="flex flex-col">
                <label for="" class="required">Grades I Teach in School</label>
                <div
                    @class([
                     'flex flex-row space-x-2',
                     'border border-red-600 px-2 py-1' => $errors->has('form.gradesITeach'),
                     ])
                    aria-label="grades i teach"
                    @error('form.gradesITeach')
                    aria-invalid="true"
                    aria-description="{{ $message }}"
                    @enderror
                >
                    @for($i=1; $i<13; $i++)
                        <div>
                            <input wire:model.blur="form.gradesITeach"
                                   wire:key="gradesITeach-{{ $i }}"
                                   type="checkbox"
                                   value="{{ $i }}"
                                   aria-label="grade {{ $i }}"
                            />
                            <label>{{ $i }}</label>
                        </div>
                    @endfor
                </div>
                @error('form.gradesITeach')
                <x-input-error messages="{{ $message }}" aria-live="polite"/>
                @enderror

            </div>

            {{-- SUBJECTS I TEACH IN SCHOOL --}}
            <div class="flex flex-col">
                <label for="" class="required">Subjects I Teach in School</label>
                <div
                    @class([
                     'flex flex-row space-x-2',
                     'border border-red-600 px-2 py-1' => $errors->has('form.subjects'),
                     ])
                    aria-label="subjects i teach"
                    @error('form.subjects')
                    aria-invalid="true"
                    aria-description="{{ $message }}"
                    @enderror
                >
                    @foreach($subjects AS $subject)
                        <div>
                            <input wire:model="form.subjects"
                                   wire:key="subjects-{{ $subject }}"
                                   type="checkbox"
                                   value="{{ $subject }}"
                                   aria-label="subject {{ $subject }}"
                            />
                            <label>{{ $subject }}</label>
                        </div>
                    @endforeach
                </div>
                @error('form.subjects')
                <x-input-error messages="{{ $message }}" aria-live="polite"/>
                @enderror

            </div>

            {{-- WORK EMAIL --}}
            <div class="flex flex-col">
                <x-forms.elements.livewire.inputTextWide label="work email"
                                                         name="form.email"
                                                         required
                                                         type="email"
                />

                {{-- EMAIL VERIFICATION ADVISORY --}}
                @if($emailVerified)
                    <div class="mt-2 text-sm text-green-600">Verified</div>
                @else
                    <div class="mt-2 text-sm text-red-600">Unverified</div>
                @endif

                {{-- COMMERCIAL EMAIL DOMAIN FOUND --}}
                @if($form->commercialWorkAddressDomain)
                    <div
                        class="mt-2 text-sm bg-red-100 text-red-600 px-2 rounded-sm"
                    >
                        Commercial emails (gmail, hotmail, etc.) are automatically unverified for school validation
                        purposes.
                        An unverified work email will preclude you from viewing any student-entered information.
                    </div>
                @endif
            </div>

            <hr/>

            <div>
                <label>In case of emergency, please provide your supervisor's name, email and best contact
                    phone.</label>
                <div class="flex flex-row space-x-2">
                    <div class="flex flex-col">
                        <x-forms.elements.livewire.inputTextWide label="supervisor name"
                                                                 name="form.supervisorName"
                                                                 type="text"
                        />
                    </div>

                    <div class="flex flex-col">
                        <x-forms.elements.livewire.inputTextWide label="email"
                                                                 name="form.supervisorEmail"
                                                                 type="email"
                        />
                    </div>

                    <div class="flex flex-col">
                        <x-forms.elements.livewire.inputTextWide label="phone"
                                                                 name="form.supervisorPhone"
                                                                 type="text"
                        />
                    </div>
                </div>


            </div>

            <hr/>

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

        {{-- CO-TEACHERS --}}
        @if($hasCoteachers)
            <div class="mt-4 p-2 border border-gray-400 w-fit rounded-lg shadow-lg">
                <h3 class="font-semibold">The following teacher(s) should have access to my students:</h3>
                @foreach($schoolTeachers AS $teacher)
                    <div class="flex flex-row space-x-2 items-center ml-2">
                        <input type="checkbox" wire:model.live="coteacherIds" value="{{ $teacher['id'] }}">
                        <label>{{ $teacher['name'] }}</label>

                    </div>
                @endforeach
            </div>
        @endif

    </form>


</div>

