<?php

namespace App\Imports;

use App\Models\Epayment;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Students\Student;
use App\Models\User;
use App\Services\ConvertToPenniesService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EpaymentSquareImport implements WithHeadings, ToModel
{
    /**
     * @param  array  $row
     */
    public function model(array $row)
    {

        $ePaymentDetails = $this->parseRow($row);

        if (count($ePaymentDetails)) {
            $exists = Epayment::query()
                ->where('version_id', $ePaymentDetails['versionId'])
                ->where('school_id', $ePaymentDetails['schoolId'])
                ->where('user_id', $ePaymentDetails['userId'])
                ->where('fee_type', $ePaymentDetails['feeType'])
                ->where('candidate_id', $ePaymentDetails['candidateId'])
                ->where('transaction_id', $ePaymentDetails['transactionId'])
                ->get();

            Epayment::updateOrCreate(
                [
                    'version_id' => $ePaymentDetails['versionId'],
                    'school_id' => $ePaymentDetails['schoolId'],
                    'user_id' => $ePaymentDetails['userId'],
                    'fee_type' => $ePaymentDetails['feeType'],
                    'candidate_id' => $ePaymentDetails['candidateId'],
                    'transaction_id' => $ePaymentDetails['transactionId'],
                ],
                [
                    'amount' => $ePaymentDetails['amount'],
                    'comments' => $ePaymentDetails['comments'],
                ]
            );

        }
    }

    public function headings(): array
    {
        return [
            'Date',
            'Time',
            'Time Zone',
            'Gross Sales',
            'Discounts',
            'Service Charges',
            'Net Sales',
            'Gift Card Sales',
            'Tax',
            'Tip',
            'Partial Refunds',
            'Total Collected',
            'Source',
            'Card',
            'Card Entry Methods',
            'Cash',
            'Square Gift Card',
            'Other Tender',
            'Other Tender Type',
            'Tender Note',
            'Fees',
            'Net Total',
            'Transaction ID',
            'Payment ID',
            'Card Brand',
            'PAN Suffix',
            'Device Name',
            'Staff Name',
            'Staff ID',
            'Details',
            'Description',
            'Event Type',
            'Location',
            'Dining Option', 'Customer ID',
            'Customer Name',
            'Customer Reference ID',
            'Device Nickname',
            'Third Party Fees',
            'Deposit ID',
            'Deposit Date',
            'Deposit Details',
            'Fee Percentage Rate',
            'Fee Fixed Rate',
            'Refund Reason',
            'Discount Name',
            'Transaction Status',
            'Cash App',
            'Order Reference ID',
            'Fulfillment Note',
            'Free Processing Applied'
        ];
    }

    private function getCandidateDetails(int $candidateSuffix, string $transactionId, int $amount): array
    {
        //hardcoded for CJMEA 2024-25 event
        $eventId = 83;
        $candidateId = (int) $eventId.$candidateSuffix;

        $candidate = Candidate::find($candidateId);
        $student = Student::find($candidate->student_id);
        $userId = $student->user->id;

        return [
            'versionId' => $candidate->version_id,
            'schoolId' => $candidate->school_id,
            'userId' => $userId,
            'feeType' => 'registration',
            'candidateId' => $candidateId,
            'transactionId' => $transactionId,
            'amount' => $amount,
            'comments' => $candidate->program_name.' square payment updated',
        ];
    }

    private function parseRow(array $row): array
    {
        //skip the header row
        if ($row[0] === 'Date') {
            return [];
        }

        //row exists and contains actionable data
        if (array_key_exists(49, $row) &&
            strlen($row[49]) &&
            (str_starts_with($row[49], 'ID:'))
        ) {
            //expected: $row[49] == 'ID: ####'
            $candidateSuffix = substr($row[49], -4);
            $transactionId = $row[22];
            $amount = ConvertToPenniesService::usdToPennies(substr($row[3], 1));

            return $this->getCandidateDetails($candidateSuffix, $transactionId, $amount);
        }

        return [];

//        $parts = explode(" | ", $row[26]);
//        $parts[] = $row[12];
//
//        return $parts;
    }


}
