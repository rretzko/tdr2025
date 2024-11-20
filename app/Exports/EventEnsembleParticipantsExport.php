<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventEnsembleParticipantsExport implements FromArray, WithHeadings
{
    public function __construct(private readonly array $participants)
    {
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->participants;
    }

    public function headings(): array
    {
        return [
            'name',
            'lastName',
            'school',
            'teacher',
            'vp',
            'vp-sort',
            'score',
            'student email',
            'student cell',
            'student home',
            'teacher email',
            'teacher cell',
            'teacher work',
            'emergency contact',
            'ec cell',
            'ec home',
            'ec work',
        ];
    }
}
