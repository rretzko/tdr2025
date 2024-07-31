<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container">

        {{-- FORM --}}
        <form wire:submit="save" class="my-4 p-4 border border-gray-200 rounded-lg shadow-lg">

            <div class="space-y-4">
                <x-forms.styles.genericStyle/>

                {{-- SYS ID --}}
                <x-forms.elements.livewire.labeledInfoOnly label="Sys.Id" wireModel="form.sysId"/>

                <fieldset id="versionDates">

                    {{-- ADMINISTRATION --}}
                    <div class="border border-white border-b-gray-200 pb-2">
                        <h3>Dates for <b>administrative</b> access:</h3>
                        <div class="flex flex-col space-y-2">
                            <div class="flex flex-row items-center space-x-2">
                                <label>Open</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.adminOpen"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['adminOpen'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-row items-center space-x-2">
                                <label>Close</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.adminClose"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['adminClose'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div> {{-- END OF ADMINISTRATION DATES --}}

                    {{-- MEMBERSHIP --}}
                    <div class="border border-white border-b-gray-200 py-2">
                        <h3>Dates for <b>membership</b> access:</h3>
                        <div class="flex flex-col space-y-2">
                            <div class="flex flex-row items-center space-x-2">
                                <label>Open</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.membershipOpen"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['membershipOpen'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-row items-center space-x-2">
                                <label>Close</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.membershipClose"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['membershipClose'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div> {{-- END OF MEMBERSHIP DATES --}}

                    {{-- FINAL TEACHER CHANGES --}}
                    <div class="border border-white border-b-gray-200 py-2">
                        <h3>Date for <b>final teacher changes</b>:</h3>
                        <div class="flex flex-col space-y-2">
                            <div class="flex flex-row items-center space-x-2">
                                <label>Date</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.finalTeacherChanges"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['finalTeacherChanges'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div> {{-- END OF FINAL TEACHER CHANGES --}}

                    {{-- POSTMARK DEADLINE --}}
                    <div class="border border-white border-b-gray-200 py-2">
                        <h3>Date for <b>postmark deadline</b>:</h3>
                        <div class="flex flex-col space-y-2">
                            <div class="flex flex-row items-center space-x-2">
                                <label>Date</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.postmarkDeadline"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['postmarkDeadline'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div> {{-- END OF POSTMARK DEADLINE --}}

                    {{-- STUDENT --}}
                    <div class="border border-white border-b-gray-200 py-2">
                        <h3>Dates for <b>student</b> access:</h3>
                        <div class="flex flex-col space-y-2">
                            <div class="flex flex-row items-center space-x-2">
                                <label>Open</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.studentOpen"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['studentOpen'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-row items-center space-x-2">
                                <label>Close</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.studentClose"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['studentClose'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div> {{-- END OF STUDENT DATES --}}

                    {{-- ADJUDICATION --}}
                    <div class="border border-white border-b-gray-200 py-2">
                        <h3>Dates for <b>judging</b> access:</h3>
                        <div class="flex flex-col space-y-2">
                            <div class="flex flex-row items-center space-x-2">
                                <label>Open</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.adjudicationOpen"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['adjudicationOpen'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-row items-center space-x-2">
                                <label>Close</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.adjudicationClose"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['adjudicationClose'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div> {{-- END OF ADJUDICATION DATES --}}

                    {{-- TAB ROOM --}}
                    <div class="border border-white border-b-gray-200 py-2">
                        <h3>Dates for <b>tab room</b> access:</h3>
                        <div class="flex flex-col space-y-2">
                            <div class="flex flex-row items-center space-x-2">
                                <label>Open</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.tabRoomOpen"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['tabRoomOpen'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-row items-center space-x-2">
                                <label>Close</label>
                                <x-forms.elements.livewire.inputDate
                                    label=""
                                    name="form.tabRoomClose"
                                    type="datetime-local"
                                />
                                {{-- SUCCESS INDICATOR --}}
                                @if($successIndicators['tabRoomClose'])
                                    <div class="text-green-600 italic text-xs">
                                        {{ $successMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div> {{-- END OF TAB ROOM DATES --}}

                </fieldset> {{-- END OF ADJUDICATION FIELDSET --}}


            </div>
        </form>

    </div>{{-- END OF ID=CONTAINER --}}

</div>

