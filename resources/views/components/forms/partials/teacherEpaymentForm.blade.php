@props([
    'amountDue',
    'customProperties',
    'email',
    'ePaymentId',
    'ePaymentVendor',
    'sandbox',
    'sandboxId',
    'sandboxPersonalEmail',
    'showSuccessIndicator',
    'successMessage',
    'teacherName',
    'versionId',
    'versionShortName',
])
<div>
    @if(! $amountDue)
        <div class="mt-4 p-2 shadow-lg">
            <div>Nothing remains to be collected.</div>
            <div>You're Paid in Full!</div>
        </div>
    @else
        @if($ePaymentVendor !== 'none')
            @if($ePaymentVendor === 'paypal')
                @if($amountDue > 0)
                    @include('components.forms.partials.epayments.paypal')
                @else
                    <div>
                        No Payment due.
                    </div>
                    @if($amountDue < 0)
                        <div>
                            Our records indicate ${{ number_format(-($amountDue), 2) }} overpayment.
                        </div>
                    @endif
                @endif
            @endif
            @if($ePaymentVendor === 'square')
                {{-- SQUARE BUTTON CODE --}}
                @include('components.forms.partials.epayments.square')
            @endif
        @endif
    @endif



    {{-- SUCCESS INDICATOR --}}
    @if($showSuccessIndicator)
        <div class="text-green-600 italic text-xs">
            {{ $successMessage }}
        </div>
    @endif
</div>

