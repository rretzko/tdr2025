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
                        :coregistrationManagerAddress="$coregistrationManagerAddress"
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

                @if($selectedTab === 'ePayments')

                    @if(! $amountDue)
                        <div class="text-start my-2">
                            Balance: $0.00. No payments due.
                        </div>
                    @endif

                    @if($amountDue < 0)
                        <div class="text-start my-2">
                            Overpayment: ${{ number_format($amountDue,2) }}.
                        </div>
                    @endif

                    <div>
                        {{-- PAYPAL BUTTON --}}
                        @if(($ePaymentVendor === 'paypal') && ($amountDue > 0))
                            <x-forms.partials.teacherEpaymentForm
                                amountDue="{{ $amountDue }}"
                                customProperties="{{ $customProperties }}"
                                email="{{ $email }}"
                                ePaymentId="{{ $ePaymentId }}"
                                ePaymentVendor="{{  $ePaymentVendor }}"
                                :sandbox="$sandbox"
                                sandboxId="{{ $sandboxId }}"
                                sandboxPersonalEmail="{{ $sandboxPersonalEmail }}"
                                showSuccessIndicator="{{ $showSuccessIndicator }}"
                                successMessage="{{ $successMessage }}"
                                teacherName="{{ $teacherName }}"
                                versionId="{{ $versionId }}"
                                versionShortName="{{ $versionShortName }}"
                            />
                        @endif

                        {{-- SQUARE BUTTON --}}
                        @if(($ePaymentVendor === 'square') && ($amountDue > 0))
                            <div class="flex flex-row justify-center w-full my-4">
                                <div class="flex flex-col w-1/2">
                                    <div>
                                        Please note: You will be asked to add your <b>School Name</b>
                                        (<span class="text-xl text-red-600">{{ $schoolName }}</span>)
                                        when paying through Square.
                                    </div>
                                    <div class="text-left">
                                        Amount Due: <b>${{number_format($amountDue, 2) }}</b>
                                    </div>
                                    @include('square.payButton')
                                    <div id="advisory" class="text-xs text-red-600 mt-2 ">
                                        Please note: Payment record updates may take as long as 24-hours during the work
                                        week and by Monday at noon over the weekend.
                                    </div>
                                </div>
                            </div>

                        @endif

                    </div>

                @endif

        </div>
    </div>

</div>
