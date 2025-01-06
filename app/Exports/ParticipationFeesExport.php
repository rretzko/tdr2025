<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipationFeesExport implements FromArray, WithHeadings
{
    public function __construct(private readonly array $payments)
    {

    }

    public function array(): array
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'student name',
            'school',
            'amount',
            'transaction id',
            'comments',
            'date',
        ];
    }
}
