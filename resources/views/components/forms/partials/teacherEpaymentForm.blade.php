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
                @include('components.forms.partials.epayments.paypal')
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

