<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventEnsembleSeniorityParticipationExport implements FromArray, WithHeadings
{
    public function __construct(private readonly array $participants)
    {
    }

    public function array(): array
    {
        return $this->parseParticipants();
    }

    /**
     * Participant:
     * {#2406 ▼ // app\Exports\EventEnsembleSeniorityParticipationExport.php:16
     *   +"programName": "Kayla Brown"
     *   +"last_name": "Brown"
     *   +"class_of": 2025
     *    +"userId": 10259
     *    +"schoolName": "Howell High School"
     *    +"teacherName": "Susan Conners"
     *    +"voicePartAbbr": "AI"
     *    +"countYears": 4
     *    +"years": array:4 [▼
     *        0 => "*"
     *        1 => "*"
     *        2 => "*"
     *        3 => "*"
     *        ]
     *   }
     * @return array
     */
    private function parseParticipants(): array
    {
        return array_map(function ($participant) {
            $parsedParticipant = [
                'name' => $participant->programName,
                'lastName' => $participant->last_name,
                'class_of' => $participant->class_of,
                'school' => $participant->schoolName,
                'teacher' => $participant->teacherName,
                'voicePart' => $participant->voicePartAbbr,
                'countYears' => $participant->countYears,
            ];

            foreach ($participant->years as $key => $year) {
                $label = $key ? 'current-'.$key : 'current';
                $parsedParticipant[$label] = $year;
            }

            return $parsedParticipant;
        }, $this->participants);
    }

    public function headings(): array
    {
        return [
            'student',
            'last name',
            'class',
            'school',
            'teacher',
            'voice part',
            'years count',
            'current',
            'current-1',
            'current-2',
            'current-3',
        ];
    }
}
