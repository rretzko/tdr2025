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
            @endif
            @if($ePaymentVendor === 'square')
                {{-- SQUARE BUTTON CODE --}}
                <div>
                    <div>
                        <div style="
overflow: auto;
display: flex;
flex-direction: column;
justify-content: flex-end;
align-items: center;
width: 259px;
background: #FFFFFF;
border: 1px solid rgba(0, 0, 0, 0.1);
box-shadow: -2px 10px 5px rgba(0, 0, 0, 0);
border-radius: 10px;
font-family: SQ Market, SQ Market, Helvetica, Arial, sans-serif;
">
                            <div style="padding: 20px;">
                                <a target="_blank" data-url="https://square.link/u/6NAX5esZ?src=embd"
                                   href="https://square.link/u/6NAX5esZ?src=embed" style="
display: inline-block;
font-size: 18px;
line-height: 48px;
height: 48px;
color: #ffffff;
min-width: 212px;
background-color: #006aff;
text-align: center;
box-shadow: 0 0 0 1px rgba(0,0,0,.1) inset;
border-radius: 6px;
">Pay now</a>
                            </div>
                        </div>

                        <script>
                            function showCheckoutWindow(e) {
                                e.preventDefault();

                                const url = document.getElementById('embedded-checkout-modal-checkout-button').getAttribute('data-url');
                                const title = 'Square Payment Links';

                                // Some platforms embed in an iframe, so we want to top window to calculate sizes correctly
                                const topWindow = window.top ? window.top : window;

                                // Fixes dual-screen position                                Most browsers          Firefox
                                const dualScreenLeft = topWindow.screenLeft !== undefined ? topWindow.screenLeft : topWindow.screenX;
                                const dualScreenTop = topWindow.screenTop !== undefined ? topWindow.screenTop : topWindow.screenY;

                                const width = topWindow.innerWidth ? topWindow.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                                const height = topWindow.innerHeight ? topWindow.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

                                const h = height * .75;
                                const w = 500;

                                const systemZoom = width / topWindow.screen.availWidth;
                                const left = (width - w) / 2 / systemZoom + dualScreenLeft;
                                const top = (height - h) / 2 / systemZoom + dualScreenTop;
                                const newWindow = window.open(url, title, `scrollbars=yes, width=${w / systemZoom}, height=${h / systemZoom}, top=${top}, left=${left}`);

                                if (window.focus) newWindow.focus();
                            }

                            // This overrides the default checkout button click handler to show the embed modal
                            // instead of opening a new tab with the given link url
                            document.getElementById('embedded-checkout-modal-checkout-button').addEventListener('click', function (e) {
                                showCheckoutWindow(e);
                            });
                        </script>
                    </div>
                </div>
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

