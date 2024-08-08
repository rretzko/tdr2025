<?php

namespace App\Http\Controllers\ePayments;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Models\Epayment;
use App\Models\Events\Paypal\PaypalIPN;
use App\Models\Events\Versions\Version;
use App\Services\ConvertToPenniesService;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class EpaymentReceiptController extends Controller
{
    private $ppipn;

    public function __construct()
    {
        logger('Got to controller! @ '.__METHOD__);
        //MACD PayPal credentials
        $this->ppipn = new PaypalIPN();

        //$this->ppipn->verifyIPN();

        //set sandbox to true
        $enable_sandbox = $this->ppipn->useSandbox();
//logger('sandbox setting: '. $enable_sandbox);

        //valid email addresses for business
        $my_email_addresses =
            [
                'morrisareahonorchoir@gmail.com',
                'rick@mfrholdings.com',
            ];
//logger(__METHOD__ . ': ' . __LINE__);
        //send confirmation email to user
        $this->send_confirmation_email = true;
        $this->send_confirmation_email_address = 'Rick Retzko <rick@mfrholdings.com>';
        $this->send_confirmation_email_from_address = 'PayPal IPN <rick@mfrholdings.com>';
//logger(__METHOD__ . ': ' . __LINE__);
        //create a log of the transaction
        $this->save_log_file = true;
//logger(__METHOD__ . ': ' . __LINE__);

        $this->store();
    }

    public function store()
    {
        logger(__METHOD__);

        if (isset($_POST) && count($_POST)) {

            logger('***** MAKE DTO *****');
            $dto = $this->makeDto();
            logger('***** LOG POST INFO *****');

            $this->logPostInfo($dto);

            $payment = $this->recordPayment($dto);
            //$payment->recordIPNPayment($dto);

            logger('***** Payment created with id: '.$payment->id.'*****');

        } else {
            logger('*** PayPal IPN Testing: $_POST NOT found');
        }

        return header("HTTP/1.1 200 OK");
    }

    private function makeDto(): array
    {
        /* TROUBLESHOOTING
        logger('***** START RAW LOGGING *****');
        foreach($_POST AS $key => $value){
            logger($key.' => '.$value);
        }
        logger('***** END RAW LOGGING *****');
        */
        /**
         * $parts contains the values for:
         * [
         *  0 => user_id,
         *  1 => registrant_id,
         *  2 => eventversion_id
         *  3 => school_id,
         *  4 => amount
         *  5 => payment_category_id
         *  6 => vendor_id
         * ]
         */

        $parts = explode(' ', $_POST['custom']);

        /** 2023-10-27  FJR: Added to resolve error; verify_sign does not appear to be a required field from PayPal */
        if (array_key_exists('verify_sign', $_POST)) {
            $parts[] = $_POST['verify_sign'];
        }

//        $a = [
//            'payment_date' => $_POST['payment_date'],
//            'payer' => $_POST['first_name'].' '.$_POST['last_name'],
//            'payer_email' => $_POST['payer_email'],
//            'payer_id' => $_POST['payer_id'],
//            'address_name' => $_POST['address_name'],
//            'address_street' => $_POST['address_street'],
//            'address_city' => $_POST['address_city'],
//            'address_state' => $_POST['address_state'],
//            'address_zip' => $_POST['address_zip'],
//            'item_name' => array_key_exists('item_name', $_POST) ? $_POST['item_name'] : 'item_name',
//            'item_number' => array_key_exists('item_number', $_POST) ? $_POST['item_number'] : 'item_number',
//            'item_name1' => array_key_exists('item_name1', $_POST) ? $_POST['item_name1'] : 'item_name1',
//            'item_number1' => array_key_exists('item_number1', $_POST) ? $_POST['item_number1'] : 'item_number1',
//            'amount' => $_POST['mc_gross'],
//            'user_id' => $this->parseValue($parts[0], 'teacher('),
//            'version_id' => $this->parseValue($parts[1], 'version('),
//            'payment_type_id' => PaymentType::EPAYMENT,
//            'school_id' => $this->parseValue($parts[2], 'school('),
//            'vendor_id' => $_POST['verify_sign'],
//            'custom' => $_POST['custom'],
//            'fee_type_id' => FeeType::REGISTRATION,
//        ];

        //only paypal payments from studentfolder.info contain a valid registrant_id
        logger('custom => '.$_POST['custom']);

        $paymentDto = $this->makePaymentDto($_POST['custom']);

        //logger('parts[1] => '.$parts[1]);
        //if($parts[1] !== 'teacher'){
        //    logger('***** registrant payment for: '.$this->registrantId($parts));
        //    $a['registrant_id'] = $this->registrantId($parts);
        //    logger('a[registrant_id] => '.$a['registrant_id']);
        //}

        return $paymentDto;
    }

    private function makePaymentDto(string $custom): array
    {
        $parts = explode(' ', $custom);
        //$parts[0] = 'teacher(348)';
        //$parts[1] = 'version(91)';
        //$parts[2] = 'school(3549)';
        //$parts[3] = 'amount(99.99)';
        //$parts[4] = 'candidate(123456)';

        return [
            'version_id' => $this->parseValue($parts[1], 'version('),
            'fee_type_id' => FeeType::REGISTRATION,
            'payment_type_id' => PaymentType::EPAYMENT,
            'school_id' => $this->parseValue($parts[2], 'school('),
            'user_id' => $this->parseValue($parts[0], 'teacher('),
            'candidate_id' => $this->parseValue($parts[4], 'candidate('),
            'amount' => $this->parseValue($parts[3], 'amount('),
            'transaction_id' => htmlspecialchars($_POST['ipn_track_id']),
            'comments' => 'receiver_id: '.htmlspecialchars($_POST['receiver_id'].', payer_id: '.htmlspecialchars($_POST['payer_id'])),
            'created_by' => config('app.super_admin'),
            'updated_by' => config('app.super_admin'),
        ];
    }

    private function parseValue(string $string, string $key): string
    {
        $removePrefix = substr($string, strlen($key));

        return substr($removePrefix, 0, -1);
    }

    /**
     * USED FOR TROUBLESHOOTING
     * @param  array  $dto
     * @return void
     */
    private function logPostInfo(array $dto)
    {
        $str = '*** START PayPal dto: '."/n/r";

        foreach ($dto as $key => $value) {
            //$str .= $key.' => '.$value."/n/r";
            logger($key.' => '.$value);
        }

        $str .= '*** END PayPal dto ***';

    }

    private function recordPayment(array $dto): Payment|null
    {
        logger('*** '.__METHOD__.': '.__LINE__.' ***');
        $parts = explode(' | ', $_POST['custom']);
        //$parts[0] = 'user(348)';
        //$parts[1] = 'version(91)';
        //$parts[2] = 'school(3549)';
        //$parts[3] = 'amount(99.99)';
        //$parts[4] = 'candidate(123456)';
        //$parts[5' = feeType ex. 'registration'
//        $parts[] = $_POST['verify_sign'];

        $candidateId = trim($parts[4]); //$this->parseValue($parts[4], 'candidate(');
        $versionId = trim($parts[1]); //$this->parseValue($parts[1], 'version(');
        $version = Version::find($versionId);
        $feeTypeId = trim($parts[5]); //$version->version_status_id == VersionStatus::ACTIVE ? FeeType::REGISTRATION : FeeType::PARTICIPATION;

//logger('_PARTS[verify_sign] = ' . $parts['verify_sign']);
        $payment = Epayment::firstOrCreate(
            [
                'version_id' => $parts[1], //$this->parseValue($parts[1], 'version('),
                'school_id' => $parts[2], //$this->parseValue($parts[2], 'school('),
                'user_id' => $parts[0], //$this->parseValue($parts[0], 'teacher('),
                'fee_type' => $parts[5], //$feeTypeId,
                'candidate_id' => $candidateId,
                'transaction_id' => htmlspecialchars($_POST['ipn_track_id']),
                'comments' => 'receiver_id: '.htmlspecialchars($_POST['receiver_id'].', payer_id: '.htmlspecialchars($_POST['payer_id'])),
            ],
            [
                'amount' => ConvertToPenniesService::usdToPennies($parts[3]),
                //($this->parseValue($parts[3], 'amount(') * 100), //convert to cents
            ]
        );

        ($payment && $payment->id)
            ? logger('*** PAYMENT MADE ***')
            : logger('!!!!! PAYMENT FAILED !!!!!');

//        if ($candidateId) {
//
//            event(new UpdateCandidateStateEvent(Candidate::find($candidateId)));
//        }

        return $payment;
    }

}
