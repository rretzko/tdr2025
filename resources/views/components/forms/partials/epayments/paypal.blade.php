<form
    action="@if($sandbox) https://www.sandbox.paypal.com/cgi-bin/webscr @else https://www.paypal.com/cgi-bin/webscr @endif "
    class="mt-4 p-2 shadow-lg"
    method="post"
    target="_blank"
>

    <div class="flex flex-row space-x-2">
        <label>Click the PayPal button to pay the Amount Due:</label>
        <div>${{ number_format($amountDue, 2) }}</div>
    </div>

    <!-- Identify your business so that you can collect the payments. -->
    <input type="hidden" name="business" value="{{ $sandbox ? $sandboxId : $epaymentId }}">
    <input type="hidden" name="notify_url"
           value="https://thedirectorsroom.com/epaymentUpdate">
    <input type="hidden" name="custom" value="{{ $customProperties }}">
    <!-- Specify a subscribe button -->
    <input type="hidden" name="cmd" value="_xclick">
    <!-- Identify the registrant -->
    <input type="hidden" name="item_name" value="{{ $versionShortName }}">
    <input type="hidden" name="item_number" value="{{ $versionId }}">
    <input type="hidden" name="on0" value="{{ $teacherName }}">
    <input type="hidden" name="email" value="{{ $sandbox ? $sandboxPersonalEmail : $email }}">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="amount" value="{{ $amountDue }}">
    <!-- display the payment button -->
    <input class="rounded-full" type="image" name="submit"
           src="{{ Storage::disk('s3')->url('pp.png') }}"
           alt="PayPal button">

</form>
