<?php

namespace App\Exports;

use App\Models\Events\Versions\VersionParticipant;
use App\Models\UserConfig;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Database\Query\Builder;

class VersionParticipantsExport implements FromQuery, WithHeadings
{
    public function query()
    {
        $versionId = UserConfig::getValue('versionId');

        return VersionParticipant::query()
            ->join('users', 'users.id', '=', 'version_participants.user_id')
            ->join('teachers', 'teachers.user_id', '=', 'users.id')
            ->join('school_teacher', 'school_teacher.teacher_id', '=', 'teachers.id')
            ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
            ->where('version_id', $versionId)
            ->select('users.last_name', 'users.first_name', 'users.middle_name',
                'schools.name as schoolName', 'version_participants.status')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name');
    }

    public function headings(): array
    {
        return ['last name', 'first name', 'middle name', 'school', 'status'];
    }
}
