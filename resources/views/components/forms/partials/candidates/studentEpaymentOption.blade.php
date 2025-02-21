<div id="Payments Checkbox"
    @class([
    "p-2 mb-2",
    "bg-gray-200 border border-gray-600 rounded-lg shadow-lg" => $versionEpaymentStudent,
    ])
>
    @if($versionEpaymentStudent)
        <div class="">
            <h3>{{ $version->name }} will accept {{ $ePaymentVendor }} payments from your
                students.</h3>
            <div class="flex flex-row space-x-2 items-center">
                <input type="checkbox" wire:model.live="teacherEpaymentStudent"/>
                <label for="epayment_student">
                    Click here to allow your students to pay through {{ $ePaymentVendor }}.
                </label>
            </div>
            <div class="text-xs italic text-green-600 ml-8">
                @if($teacherEpaymentStudent)
                    Last Updated: {{ $teacherEpaymentStudentLastUpdated }}
                @endif
            </div>
            @if(!$hasEpaymentId)
                <div class="border border-gray-600 mt-2 mx-4 p-2 text-sm bg-gray-300 shadow-lg">
                    Please note: The Square credentials are currently pending. Your students will be able to use Square
                    payments as soon as the credentials are confirmed.
                </div>
            @endif
        </div>
    @endif
</div>
