<?php

namespace App\Exports;

use App\Models\Events\Versions\VersionRole;
use App\Models\UserConfig;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VersionRolesExport implements FromQuery, WithHeadings
{

    public function headings(): array
    {
        return ['last name', 'first name', 'middle name', 'school', 'role'];
    }

    public function query()
    {
        $versionId = UserConfig::getValue('versionId');

        return VersionRole::query()
            ->join('version_participants', 'version_participants.id', '=', 'version_roles.version_participant_id')
            ->join('users', 'users.id', '=', 'version_participants.user_id')
            ->join('teachers', 'teachers.user_id', '=', 'users.id')
            ->join('school_teacher', 'school_teacher.teacher_id', '=', 'teachers.id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->where('version_roles.version_id', $versionId)
            ->select('users.last_name', 'users.first_name', 'users.middle_name',
                'schools.name as schoolName', 'version_roles.role')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name');
    }
}
