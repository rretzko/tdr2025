<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

readonly class ParticipatingTeachersExport implements FromArray, WithHeadings
{
    public function __construct(
        private array $teachers,
    ) {
    }

    public function array(): array
    {
        return $this->mapArray();
    }

    private function mapArray(): array
    {
        $a = [];
        foreach ($this->teachers as $teacher) {

            $a[] = [
                'prefix_name' => $teacher->prefix_name,
                'first_name' => $teacher->first_name,
                'middle_name' => $teacher->middle_name,
                'last_name' => $teacher->last_name,
                'suffix_name' => $teacher->suffix_name,
                'full_name' => $teacher->name,
                'email' => $teacher->email,
                'phone_cell' => $teacher->phoneMobile,
                'phone_work' => $teacher->phoneWork,
                'school_name' => $teacher->schoolName,
                'registrant#' => $teacher->candidateCount,
            ];
        }

        return $a;
    }

    public function headings(): array
    {
        return [
            'prefix_name',
            'first_name',
            'middle_name',
            'last_name',
            'suffix_name',
            'full_name',
            'email',
            'phone_cell',
            'phone_work',
            'school_name',
            'registrant#',
        ];
    }
}
