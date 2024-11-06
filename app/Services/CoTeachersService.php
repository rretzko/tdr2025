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
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $myTeacherId = $teacher->id;
        $schoolIds = $teacher->schools()->pluck('schools.id')->toArray();
        $teacherIds[] = $myTeacherId;

        $coteacherIds = Coteacher::query()
            ->where('coteacher_id', $myTeacherId)
            ->whereIn('school_id', $schoolIds)
            ->pluck('teacher_id')
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
