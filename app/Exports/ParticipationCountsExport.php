<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipationCountsExport implements FromArray, WithHeadings
{
    public function __construct(private readonly array $rows)
    {
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'county',
            'obligated',
            'participating',
            'students',
            'registration manager'
        ];
    }
}
