<?php

namespace App\Http\Controllers\Founders;

use App\Http\Controllers\Controller;
use App\Models\Epayment;
use App\Services\ConvertToPenniesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaypalManualEntryController extends Controller
{
    /**
     * $request['paypalData'] should be something like:
     * 12541 | 82 | 3497 | 20 | 827735 | registration | Aidan Danner | 6RM26616XE222822M
     *
     * used exclusively by the founder.
     * No validation added.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $parts = explode('|', $request['paypalData']);
        $trimmed = array_map('trim', $parts);

        //early exit
        if (count($trimmed) !== 8) {
            return Redirect::back()->withErrors('error',
                'Less than eight properties in: "'.$request['paypalData'].'".');
        }

        $ePayment = new Epayment();

        $ePayment->user_id = $parts[0];
        $ePayment->version_id = $parts[1];
        $ePayment->school_id = $parts[2];
        $ePayment->amount = ConvertToPenniesService::usdToPennies($parts[3]);
        $ePayment->candidate_id = $parts[4];
        $ePayment->fee_type = $parts[5];
        $ePayment->transaction_id = $parts[7];

        $ePayment->save();

        $saved = (Epayment::where('transaction_id', $parts[7])->exists());

        return ($saved)
            ? Redirect::back()->with('success', 'Payment from: '.$parts[6].' saved.')
            : Redirect::back()->withErrors('error', 'Unable to save payment from '.$parts[6].'.');

    }
}
