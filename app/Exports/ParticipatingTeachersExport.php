<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipatingTeachersExport implements FromQuery, WithHeadings
{
    public function __construct(
        private readonly int $versionId,
        private readonly array $schoolIds,
        private readonly array $teacherIds
    ) {
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return DB::table('school_teacher')
            ->join('teachers', 'teachers.id', '=', 'school_teacher.teacher_id')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->join('candidates', 'candidates.teacher_id', '=', 'teachers.id')
            ->whereIn('school_teacher.school_id', $this->schoolIds)
            ->whereIn('school_teacher.teacher_id', $this->teacherIds)
            ->where('candidates.version_id', $this->versionId)
            ->where('candidates.status', 'registered')
            ->select('users.prefix_name', 'users.first_name', 'users.middle_name', 'users.last_name',
                'users.suffix_name',
                'users.name',
                'schools.name AS schoolName',
                DB::raw('COUNT(candidates.id) AS candidateCount'))
            ->groupBy(
                'school_teacher.school_id',
                'school_teacher.teacher_id',
                'users.prefix_name',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.suffix_name',
                'schools.name',
                'users.name'
            )
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->orderBy('schools.name');

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
            'school_name',
            'registrant#',
        ];
    }
}
