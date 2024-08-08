<div
    class="flex flex-col text-xs md:text-lg justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    {{-- PAGE CONTENT --}}
    <div class="w-11/12">

        {{-- HEADER and ADD-NEW and EXPORT BUTTONS --}}
        <div class="flex justify-between mb-1">
            <div>{{ ucwords($dto['header']) }} </div>
        </div>

        {{-- TABS --}}
        <x-tabs.estimateTabs :tabs="$tabs" :selected-tab="$selectedTab"/>


        {{-- FILTERS and TABLE --}}
        <div class="flex flex-col">

            @if($selectedTab === 'estimate')
                <div class="w-11/12 mx-auto">
                    <x-tables.estimateTableForRegistrants
                        :columnHeaders="$columnHeaders"
                        :header="$dto['header']"
                        registrationFee="{{  $registrationFee }}"
                        :rows="$registrants"
                        sortAsc="{{  $sortAsc }}"
                        sortColLabel="{{  $sortColLabel }}"
                        versionId="{{  $versionId }}"
                    />
                </div>
            @endif

            @if($selectedTab === 'payments')
                <div>
                    @if($showEditForm)
                        <x-forms.partials.studentPaymentForm
                            :paymentTypes="$paymentTypes"
                            showSuccessIndicator="{{ $showSuccessIndicator }}"
                            studentFullName="{{ $form->studentFullName }}"
                            successMessage="{{ $successMessage }}"
                            sysId="{{ $form->sysId }}"

                        />
                    @endif

                    @if($addNewPayment)
                        <x-forms.partials.addStudentPaymentForm
                            :candidates="$candidates"
                            :form="$form"
                            :paymentTypes="$paymentTypes"
                            showSuccessIndicator="{{ $showSuccessIndicator }}"
                            studentFullName="{{ $form->studentFullName }}"
                            successMessage="{{ $successMessage }}"
                            sysId="{{ $form->sysId }}"

                        />
                    @endif
                </div>

                {{-- STUDENT PAYMENTS TABLE --}}
                <div>
                    <x-tables.studentPaymentsTable
                        :columnHeaders="$studentPaymentColumnHeaders"
                        header="Student Payments"
                        :rows="$studentPayments"
                        sortAsc="{{ $sortAsc }}"
                        sortColLabel="{{ $sortColLabel }}"
                    />
                </div>
                @endif

                @if($selectedTab === 'payPal')
                    <div>
                        <x-forms.partials.teacherEpaymentForm
                            amountDue="{{ $amountDue }}"
                            customProperties="{{ $customProperties }}"
                            email="{{ $email }}"
                            ePaymentId="{{ $ePaymentId }}"
                            :sandbox="$sandbox"
                            sandboxId="{{ $sandboxId }}"
                            sandboxPersonalEmail="{{ $sandboxPersonalEmail }}"
                            showSuccessIndicator="{{ $showSuccessIndicator }}"
                            successMessage="{{ $successMessage }}"
                            teacherName="{{ $teacherName }}"
                            versionId="{{ $versionId }}"
                            versionShortName="{{ $versionShortName }}"
                        />
                    </div>
                @endif

        </div>
    </div>

</div>
