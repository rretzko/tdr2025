<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">

        <div id="tranferFrom" class="bg-red-50 pb-2 px-2 items-center rounded-lg shadow-lg w-full">

            <h3 class="font-semibold text-center">Transfer From</h3>

            <fieldset id="fromVars">

                {{-- SCHOOL FROM --}}
                <div class="flex flex-col mb-2">
                    <label for="schoolIdFrom">School</label>
                    <select wire:model.live.debounce="schoolIdFrom" class="w-11/12" autofocus>
                        <option value="0">- select -</option>
                        @foreach($schools AS $school)
                            <option value="{{ $school['id'] }}" class="text-sm">
                                {{ $school['name'] }} ({{ $school['countyName'] . ' ' . $school['abbr'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TEACHER FROM --}}
                <div class="flex flex-col mb-2">
                    <label for="teacherIdFrom">Teacher</label>
                    <select wire:model.live.debounce="teacherIdFrom" class="w-11/12">
                        <option value="0">- select -</option>
                        @foreach($teacherFroms AS $teacherFrom)
                            <option value="{{ $teacherFrom['id'] }}" class="text-sm">
                                {{ $teacherFrom['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TRANSFER ERROR --}}
                @if(count($transferErrors))
                    @foreach($transferErrors AS $message)
                        <div class="text-red-600">
                            {{ $message }}
                        </div>
                    @endforeach
                @endif

                {{-- STUDENTS FROM --}}
                <div class="flex flex-col mb-2">
                    <label for="studentIdFrom" class="font-semibold underline">
                        Students <span class="text-xs">({{ count($studentFroms) }})</span>
                    </label>
                    @forelse($studentFroms AS $studentFrom)
                        <div class="flex flex-row space-x-2 ml-2 items-center"
                             wire:key="studentFrom-{{ $studentFrom['id'] }}">
                            <input
                                type="checkbox"
                                wire:model.live="studentIdFroms"
                                value="{{ $studentFrom['id'] }}"
                                class="w-3 h-3"
                            />
                            <label for="" class="text-xs">{{ $studentFrom['name'] }} ({{ $studentFrom['class_of'] }}
                                )</label>
                        </div>
                    @empty
                        <div class="text-xs ml-2">
                            No current students found.
                        </div>
                    @endforelse
                </div>

            </fieldset>{{-- end of fromVars --}}

        </div>

        <div id="tranferTo" class="bg-green-50 pb-2 px-2 items-center shadow-lg w-full">

            <h3 class="font-semibold text-center">Transfer To</h3>

            <fieldset id="toVars">

                {{-- SCHOOL TO --}}
                <div class="flex flex-col mb-2">
                    <label for="schoolIdTo">School</label>
                    <select wire:model.live.debounce="schoolIdTo" class="w-11/12">
                        <option value="0">- select -</option>
                        @foreach($schools AS $school)
                            <option value="{{ $school['id'] }}" class="text-sm">
                                {{ $school['name'] }} ({{ $school['countyName'] . ' ' . $school['abbr'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TEACHER TO --}}
                <div class="flex flex-col mb-2">
                    <label for="teacherIdTo">Teacher</label>
                    <select wire:model.live.debounce="teacherIdTo" class="w-11/12">
                        <option value="0">- select -</option>
                        @foreach($teacherTos AS $teacherTo)
                            <option value="{{ $teacherTo['id'] }}" class="text-sm">
                                {{ $teacherTo['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CURRENT STUDENTS --}}
                <div class="flex flex-col mb-2">
                    <label for="studentIdTo" class="font-semibold underline">
                        Current Students <span class="text-xs">({{ count($studentTos) }})</span>
                    </label>
                    @forelse($studentTos AS $studentTo)
                        <div class="flex flex-row space-x-2 ml-2">
                            <label for="" class="ml-2 text-xs">{{ $studentTo['name'] }} ({{ $studentTo['class_of'] }}
                                )</label>
                        </div>
                    @empty
                        <div class="ml-2 text-xs">
                            No current students found.
                        </div>
                    @endforelse
                </div>{{-- end of toVars --}}

                {{-- TRANSFER BUTTON --}}
                <button
                    type="button"
                    wire:click="transferStudents"
                    @class([
                        'bg-gray-500 text-gray-300 text-sm px-2 rounded-lg shadow-lg ',
                        'bg-green-500 text-white' => ($schoolIdTo && $teacherIdTo && count($studentIdFroms))
                    ])
                    @disabled(! (count($studentIdFroms) && $schoolIdTo && $teacherIdTo))
                >
                    Transfer {{ count($studentIdFroms) }} Students
                </button>

                <div>@json($studentIdFroms)</div>

            </fieldset>{{-- end of toVars --}}
        </div>

        {{-- FORM --}}
        {{--        <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">--}}

        {{--            <div class="space-y-4">--}}
        {{--                <x-forms.styles.genericStyle/>--}}

        {{--                --}}{{-- SYS ID --}}
        {{--                <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="form.sysId"/>--}}

        {{--                <fieldset id="versionDates">--}}

        {{--                    --}}{{-- ADMINISTRATION --}}
        {{--                    <div class="border border-white border-b-gray-200 pb-2">--}}
        {{--                        <h3>Dates for <b>administrative</b> access:</h3>--}}
        {{--                        <div class="flex flex-col space-y-2">--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Open</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.adminOpen"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['adminOpen'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Close</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.adminClose"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['adminClose'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div> --}}{{-- END OF ADMINISTRATION DATES --}}

        {{--                    --}}{{-- MEMBERSHIP --}}
        {{--                    <div class="border border-white border-b-gray-200 py-2">--}}
        {{--                        <h3>Dates for <b>membership</b> access:</h3>--}}
        {{--                        <div class="flex flex-col space-y-2">--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Open</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.membershipOpen"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['membershipOpen'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Close</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.membershipClose"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['membershipClose'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div> --}}{{-- END OF MEMBERSHIP DATES --}}

        {{--                    --}}{{-- FINAL TEACHER CHANGES --}}
        {{--                    <div class="border border-white border-b-gray-200 py-2">--}}
        {{--                        <h3>Date for <b>final teacher changes</b>:</h3>--}}
        {{--                        <div class="flex flex-col space-y-2">--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Date</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.finalTeacherChanges"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['finalTeacherChanges'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div> --}}{{-- END OF FINAL TEACHER CHANGES --}}

        {{--                    --}}{{-- POSTMARK DEADLINE --}}
        {{--                    <div class="border border-white border-b-gray-200 py-2">--}}
        {{--                        <h3>Date for <b>postmark deadline</b>:</h3>--}}
        {{--                        <div class="flex flex-col space-y-2">--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Date</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.postmarkDeadline"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['postmarkDeadline'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div> --}}{{-- END OF POSTMARK DEADLINE --}}

        {{--                    --}}{{-- STUDENT --}}
        {{--                    <div class="border border-white border-b-gray-200 py-2">--}}
        {{--                        <h3>Dates for <b>student</b> access:</h3>--}}
        {{--                        <div class="flex flex-col space-y-2">--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Open</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.studentOpen"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['studentOpen'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Close</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.studentClose"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['studentClose'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div> --}}{{-- END OF STUDENT DATES --}}

        {{--                    --}}{{-- ADJUDICATION --}}
        {{--                    <div class="border border-white border-b-gray-200 py-2">--}}
        {{--                        <h3>Dates for <b>judging</b> access:</h3>--}}
        {{--                        <div class="flex flex-col space-y-2">--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Open</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.adjudicationOpen"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['adjudicationOpen'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Close</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.adjudicationClose"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['adjudicationClose'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div> --}}{{-- END OF ADJUDICATION DATES --}}

        {{--                    --}}{{-- TAB ROOM --}}
        {{--                    <div class="border border-white border-b-gray-200 py-2">--}}
        {{--                        <h3>Dates for <b>tab room</b> access:</h3>--}}
        {{--                        <div class="flex flex-col space-y-2">--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Open</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.tabRoomOpen"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['tabRoomOpen'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                            <div class="flex flex-row items-center space-x-2">--}}
        {{--                                <label>Close</label>--}}
        {{--                                <x-forms.elements.livewire.inputDate--}}
        {{--                                    label=""--}}
        {{--                                    name="form.tabRoomClose"--}}
        {{--                                    type="datetime-local"--}}
        {{--                                />--}}
        {{--                                --}}{{-- SUCCESS INDICATOR --}}
        {{--                                @if($successIndicators['tabRoomClose'])--}}
        {{--                                    <div class="text-green-600 italic text-xs">--}}
        {{--                                        {{ $successMessage }}--}}
        {{--                                    </div>--}}
        {{--                                @endif--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div> --}}{{-- END OF TAB ROOM DATES --}}

        {{--                </fieldset> --}}{{-- END OF ADJUDICATION FIELDSET --}}


        {{--            </div>--}}
        {{--        </form>--}}

    </div>{{-- END OF ID=CONTAINER --}}

</div>


