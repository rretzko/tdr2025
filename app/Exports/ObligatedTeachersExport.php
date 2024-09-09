<?php

namespace App\Exports;

use App\Models\Events\Versions\VersionConfigMembership;
use App\Models\UserConfig;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ObligatedTeachersExport implements FromQuery, WithHeadings
{
    public function __construct(private readonly bool $membershipCardRequired, private readonly int $versionId)
    {
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return DB::table('obligations')
            ->join('users', 'users.id', '=', 'obligations.teacher_id')
            ->join('teachers', 'teachers.id', '=', 'obligations.teacher_id')
            ->join('school_teacher', 'school_teacher.teacher_id', '=', 'obligations.teacher_id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->join('school_grades', 'school_grades.school_id', '=', 'schools.id')
            ->where('version_id', $this->versionId)
            ->where('school_teacher.active', 1)
            ->select('obligations.accepted',
                'users.prefix_name', 'users.first_name', 'users.middle_name', 'users.last_name', 'users.suffix_name',
                'users.name',
                'schools.name AS schoolName',
                DB::raw('GROUP_CONCAT(school_grades.grade ORDER BY school_grades.grade ASC SEPARATOR ", ") AS grades')
            )
            ->groupBy(
                'obligations.accepted',
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
        $a = [
            'accepted',
            'prefix_name',
            'first_name',
            'middle_name',
            'last_name',
            'suffix_name',
            'full_name',
            'school_name',
            'grades',
        ];

        if ($this->membershipCardRequired) {
            $a[] = 'expiration';
        }

        return $a;
    }
}
