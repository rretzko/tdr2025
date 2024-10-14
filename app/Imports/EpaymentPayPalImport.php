<?php

namespace App\Imports;

use App\Models\Epayment;
use App\Services\ConvertToPenniesService;
use App\Services\ConvertToUsdService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EpaymentPayPalImport implements WithHeadings, WithStartRow, ToModel
{
    /**
     * @param  Collection  $collection
     */
    public function model(array $row)
    {
        $ids = $this->parseRow($row);
        if (count($ids)) {

            Epayment::updateOrCreate(
                [
                    'version_id' => $ids[1],
                    'school_id' => $ids[2],
                    'user_id' => $ids[0],
                    'fee_type' => $ids[5],
                    'candidate_id' => $ids[4],
                    'transaction_id' => $ids[7],
                ],
                [
                    'amount' => (int) ConvertToPenniesService::usdToPennies($ids[3]),
                    'comments' => $ids[6].' payment uploaded',
                ]
            );
        }

    }

//    public function mapping(): array
//    {
//        // TODO: Implement mapping() method.
//    }

    private function parseRow(array $row): array
    {
        if ($row[0] === 'Date') {
            return [];
        }

        $parts = explode(" | ", $row[26]);
        $parts[] = $row[12];

        return $parts;
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

    public function startRow(): int
    {
        return 1;
    }
}
