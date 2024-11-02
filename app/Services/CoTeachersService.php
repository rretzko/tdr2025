<?php

namespace App\Services;

use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\Coteacher;
use App\Models\SchoolStudent;
use App\Models\UserConfig;

class CoTeachersService
{
    /**
     * @return array
     */
    public static function getCoTeachersIds(): array
    {
        $teacherIds = [];
        $myTeacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $schoolId = UserConfig::getValue('schoolId');
        $teacherIds[] = $myTeacherId;

        $coteacherIds = Coteacher::query()
            ->where('teacher_id', $myTeacherId)
            ->where('school_id', $schoolId)
            ->pluck('coteacher_id')
            ->toArray();

        return array_merge($teacherIds, $coteacherIds);
    }

    public static function userIsCoTeacherAtStudentsSchool(int $studentId): bool
    {
        $schoolId = SchoolStudent::query()
            ->where('student_id', $studentId)
            ->where('active', 1)
            ->value('school_id');

        $myTeacherId = Teacher::where('user_id', auth()->id())->first()->id;

        return Coteacher::query()
            ->where('school_id', $schoolId)
            ->where('coteacher_id', $myTeacherId)
            ->exists();
    }
}
