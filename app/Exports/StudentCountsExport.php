<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentCountsExport implements FromArray, WithHeadings
{
    public function __construct(
        private readonly array $rows,
        private readonly Collection $voiceParts
    ) {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        $headings = [
            '###',
            'school',
            'teacher',
        ];

        foreach ($this->voiceParts as $voicePart) {
            $headings[] = $voicePart->abbr;
        }

        $headings[] = 'total';

        return $headings;
    }
}
