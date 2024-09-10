@props([
    'amountDue',
    'customProperties',
    'email',
    'ePaymentId',
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
    @if(! $form->usdAmountDue)
        <div class="mt-4 p-2 shadow-lg">
            <div>Nothing remains to be collected.</div>
            <div>You're Paid in Full!</div>
        </div>
    @else
        <form wire:submit="save" class="bg-gray-100 border-gray-600 mt-4 p-2 shadow-lg">

            <div class="flex flex-row space-x-2 border border-b-gray-800">
                <label>{{ $form->schoolName }} Amount Due:</label>
                <div class="font-semibold">${{ number_format($form->usdAmountDue, 2) }}</div>
            </div>

            <fieldset class="flex flex-col md:flex-row space-x-2">

                {{-- PAID BY --}}
                <div class="flex flex-col mr-4">
                    <label for="form.usdPayment">Paid By</label>
                    <div class="ml-4">
                        <div class="flex flex-col">
                            <div class="flex flex-row space-x-2 items-center">
                                <input type="radio" wire:model.live="form.paidBy" value="check"/>
                                <label for="form.paidBy['check']">Check</label>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <div class="flex flex-row space-x-2 items-center">
                                <input type="radio" wire:model="formpaidBye" value="purchase order"/>
                                <label for="form.paidBy['check']">Purchase Order</label>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <div class="flex flex-row space-x-2 items-center">
                                <input type="radio" wire:model="form.paidBy" value="cash"/>
                                <label for="form.paidBy['check']">Cash</label>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <div class="flex flex-row space-x-2 items-center">
                                <input type="radio" wire:model="form.paidBy" value="other"/>
                                <label for="form.paidBy['check']">Other</label>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- AMOUNT --}}
                <div class="flex flex-col">
                    <label for="form.usdPayment">Amount</label>
                    <input type="text" wire:model="form.usdPayment" class="w-24" autofocus required/>
                </div>


                {{-- TRANSACTION ID --}}
                <div class="flex flex-col">
                    <label for="form.transactionId">Check #/Transaction ID</label>
                    <input type="text" wire:model="form.transactionId" class="w-48"/>
                </div>

                {{-- COMMENTS --}}
                <div class="flex flex-col">
                    <label for="form.comments">Comments</label>
                    <textarea wire:model="form.comments" rows="4" cols="20"></textarea>
                </div>
            </fieldset>

            <!-- display the payment button -->
            <button
                type="submit"
                class="bg-gray-800 text-gray-100 px-2 text-sm rounded-lg shadow-lg"
            >
                Submit
            </button>

            <!-- close the form -->
            <button
                type="button"
                wire:click="$toggle('showPaymentForm')"
                class="bg-gray-600 text-gray-100 px-2 text-sm rounded-lg shadow-lg"
            >
                Close Form
            </button>

        </form>
    @endif

    {{-- SUCCESS INDICATOR --}}
    @if($showSuccessIndicator)
        <div class="text-green-600 italic text-xs">
            {{ $successMessage }}
        </div>
    @endif
</div>

