<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipatingStudentsExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly int $versionId)
    {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): \Illuminate\Support\Collection
    {
        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS teacher', 'teacher.id', '=', 'teachers.user_id')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users AS student', 'student.id', '=', 'students.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->select('schools.name as schoolName',
                DB::raw("CONCAT(teacher.last_name, ', ', teacher.first_name, ' ', teacher.middle_name) AS teacherFullName"),
                DB::raw("CONCAT(student.last_name, ', ', student.first_name, ' ', student.middle_name) AS studentFullName"),
                'voice_parts.descr AS voicePartDescr')
            ->orderBy('schools.name')
            ->orderBy('teacherFullName')
            ->orderBy('studentFullName')
            ->get();
    }

    public function headings(): array
    {
        return [
            'school', 'teacher', 'registrant', 'voice part'
        ];
    }
}
