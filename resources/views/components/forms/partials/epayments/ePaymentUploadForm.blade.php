<div class="p-2">
    <form method="post" action="{{ route('founder.epaymentUpload') }}" enctype="multipart/form-data">
        @csrf
        <label class="mb-4">ePayment CSV Upload</label>

        {{-- EPAYMENT VENDOR --}}
        <fieldset class="flex flex-col">
            <div class="flex flex-col">
                <label class="font-semibold">Vendor</label>
                <div class="ml-2">
                    <fieldset class="flex flex-row space-x-1 items-center">
                        <input type="radio" name="vendor" value="paypal" checked>
                        <label>PayPal</label>
                    </fieldset>
                    <fieldset class="flex flex-row space-x-1 items-center">
                        <input type="radio" name="vendor" value="square">
                        <label>Square</label>
                    </fieldset>
                </div>
            </div>
        </fieldset>

        {{-- FILE UPLOAD --}}
        <fieldset class="flex flex-col mb-2">
            <label class="font-semibold">Select File</label>
            <div class="ml-2">
                <input type="file" accept="text/csv" name="transaction" required/>
            </div>
        </fieldset>

        <input class="bg-gray-800 text-white w-fit px-2 rounded-full" type="submit" name="submit"
               value="Submit"/>
    </form>
</div>
