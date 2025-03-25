@props([
    'amount',
    'comments',
    'paymentTypes', //cash, check
    'showSuccessIndicator',
    'successMessage',
    'transaction_id',
])
<form wire:submit="save" class="space-y-4 border-gray-600 shadow-lg mt-4 px-4 pb-4">

    {{-- SYSID, NAME --}}
    <fieldset class="flex flex-col space-y-2">
        <x-forms.elements.livewire.labeledInfoOnly
            label="sysId"
            wireModel="form.sysId"
        />

        <x-forms.elements.livewire.labeledInfoOnly
            label="name"
            wireModel="form.studentFullName"
        />
    </fieldset>

    {{-- PAYMENT TYPE, AMOUNT --}}
    <fieldset class="flex flex-row space-x-1">

        <x-forms.elements.livewire.selectCompressed
            label="type"
            name="form.paymentType"
            :options="$paymentTypes"
            required="true"
        />

        <x-forms.elements.livewire.inputTextCompressed
            label="amount"
            name="form.amount"
        />
    </fieldset>

    {{-- TRANSACTION ID, COMMENTS --}}
    <fieldset class="flex flex-col space-y-1">

        <x-forms.elements.livewire.inputTextCompressed
            label="transaction id"
            name="form.transactionId"
        />

        <x-forms.elements.livewire.inputTextArea
            label="comments"
            name="form.comments"
        />
    </fieldset>

    <button wire:click="$set('showEditForm',0)"
            class="bg-gray-800 text-white rounded-full px-2 text-xs"
            type="button"
    >
        Close Form
    </button>

    {{-- SUCCESS INDICATOR --}}
    @if($showSuccessIndicator)
        <div class="text-green-600 italic text-xs">
            {{ $successMessage }}
        </div>
    @endif

</form>
