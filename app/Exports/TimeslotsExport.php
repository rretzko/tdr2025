<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimeslotsExport implements FromArray, WithHeadings
{
    private array $rows = [];

    public function array(): array
    {
        return $this->getRows();
    }

    private function getRows(): array
    {
        return [];
    }

    public function headings(): array
    {
        $headers = [
            'school', 'teacher'
        ];

        $merged = array_merge($headers, $this->voicePartHeaders());

        $merged[] = 'total';
        $merged[] = 'timeslot';

        return $merged;
    }

    private function voicePartHeaders(): array
    {
        return [];
    }
}
