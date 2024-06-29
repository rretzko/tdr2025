<?php

namespace App\Exports;

use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SchoolEnsembleMembersExport implements FromQuery, WithHeadings
{
    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return DB::table('ensemble_members')
            ->join('students', 'ensemble_members.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('schools', 'ensemble_members.school_id', '=', 'schools.id')
            ->join('ensembles', 'ensemble_members.ensemble_id', '=', 'ensembles.id')
            ->join('voice_parts', 'ensemble_members.voice_part_id', '=', 'voice_parts.id')
            ->select('users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
                'voice_parts.descr AS voicePartDescr', 'students.class_of', 'schools.name AS schoolName',
                'ensembles.name AS ensembleName', 'ensemble_members.school_year', 'ensemble_members.status',
                'ensemble_members.office')
            ->orderBy('users.last_name');
    }

    public function headings(): array
    {
        return [
            'last', 'first', 'middle', 'suffix',
            'voice part', 'class of', 'schoolName', 'ensembleName', 'school year',
            'status', 'office',
        ];
    }
}
