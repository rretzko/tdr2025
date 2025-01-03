<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

readonly class ParticipatingStudentsExport implements FromArray, WithHeadings
{
    public function __construct(private array $students)
    {
    }

    public function headings(): array
    {
        return [
            'school',
            'teacher', 'teacher-email', 'teacher_cell', 'teacher_home',
            'first_name', 'middle_name', 'last_name',
            'voice_part', 'grade', 'class_of',
        ];
    }

    public function array(): array
    {
        return $this->mapStudents();
    }

    private function mapStudents(): array
    {
        $a = [];

        foreach ($this->students as $student) {

            $a[] = [
                'school' => $student->schoolName,
                'teacher' => $student->teacherFullName,
                'teacher_email' => $student->email,
                'teacher_cell' => $student->phoneMobile,
                'teacher_work' => $student->phoneWork,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'voice_part' => $student->voicePartDescr,
                'grade' => $student->grade,
                'class_of' => $student->class_of,
            ];
        }

        return $a;
    }
}
