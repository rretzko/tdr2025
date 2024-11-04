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
            'TimeZone',
            'Name',
            'Type',
            'Status',
            'Currency',
            'Gross',
            'Fee',
            'Net',
            'From Email Address',
            'To Email Address',
            'Transaction ID', //this is important index=12
            'Shipping Address',
            'Address Status',
            'Item Title',
            'Item ID',
            'Shipping and Handling Amount',
            'Insurance Amount',
            'Sales Tax',
            'Option 1 Name',
            'Option 1 Value',
            'Option 2 Name',
            'Option 2 Value',
            'Reference Txn ID',
            'Invoice Number',
            'Custom Number',  //this is important index=26
            'Quantity',
            'Receipt ID',
            'Balance',
            'Address Line 1',
            'Address Line 2/District/Neighborhood',
            'Town/City',
            'State/Province/Region/County/Territory/Prefecture/Republic',
            'Zip/Postal Code',
            'Country',
            'Contact Phone Number',
            'Subject',
            'Note',
            'Payment Tracking ID',
            'Country Code',
            'Balance Impact',
            'Invoice Number',
            'Payflow Transaction ID (PNREF)',

        ];
    }

    private function getCandidateDetails(int $candidateSuffix, string $transactionId, float $amount): array
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
        //dd($row[49]);
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
