<?php

namespace App\Imports;

use App\Models\EmergencyContact;
use App\Models\Epayment;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Students\Student;
use App\Models\User;
use App\Services\ConvertToPenniesService;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EpaymentSquareImport implements WithHeadings, ToModel
{
    /**
     * @param  array  $row
     */
    public function model(array $row)
    {
        static $rowCounter = 1;

        $ePaymentDetails = $this->parseRow($row, $rowCounter);

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

        $rowCounter++;
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

    private function getCandidate(int $candidateSuffix, string $payer, int $rowCounter): Candidate|null
    {
        //hardcoded for NJMEA 2025-26 event
        $eventId = 84;
        $candidateId = (int) $eventId.$candidateSuffix;

        $candidate = Candidate::find($candidateId);

        if (!$candidate) {
            $candidate = $this->getCandidateThroughPayer($payer);
        }

        if (!$candidate) {
            Log::error("*** Candidate id: $candidateId with payer: $payer not found at row: $rowCounter. ***");
            return null;
        }

        return $candidate;
    }

    private function getCandidateDetails(Candidate $candidate, string $transactionId, int $amount, string $payer): array
    {
        $student = Student::find($candidate->student_id);
        $userId = $student->user->id;

        return [
            'versionId' => $candidate->version_id,
            'schoolId' => $candidate->school_id,
            'userId' => $userId,
            'feeType' => 'registration',
            'candidateId' => $candidate->id,
            'transactionId' => $transactionId,
            'amount' => $amount,
            'comments' => $candidate->program_name.' square payment updated',
        ];
    }

    private function getCandidateThroughPayer(string $payer): Candidate|null
    {
        $versionId = 83; //hard-coded to CJMEA 2024-35 event
        $studentIds = EmergencyContact::where('name', 'LIKE', '%'.$payer.'%')
            ->pluck('student_id')
            ->toArray();

        foreach ($studentIds as $studentId) {

            $candidate = Candidate::query()
                ->where('student_id', $studentId)
                ->where('version_id', $versionId)
                ->first();

            if ($candidate) {
                return $candidate;
            }
        }

        return null;

    }

    private function parseRow(array $row, int $rowCounter): array
    {
        //skip the header row
        if ($row[0] === 'Date') {
            return [];
        }

        //row exists and contains actionable data
        if (array_key_exists(49, $row) &&
            strlen($row[49]) &&
            (str_starts_with($row[49], 'ID:') || str_starts_with($row[49], 'Student id:'))
        ) {
            //expected: $row[49] == 'ID: ####'
            $candidateSuffix = substr($row[49], -4);
            $transactionId = $row[22];
            $amount = ConvertToPenniesService::usdToPennies(substr($row[3], 1));
            $payer = $row[35];

            $candidate = $this->getCandidate($candidateSuffix, $payer, $rowCounter);

            if (is_null($candidate)) {
                return [];
            }

            return $this->getCandidateDetails($candidate, $transactionId, $amount, $payer);
        }

        return [];
    }


}
