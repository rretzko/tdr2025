<?php

namespace App\Services;

use App\Models\Libraries\LibLibrarian;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\Coteacher;
use App\Models\SchoolStudent;
use App\Models\UserConfig;

class CoTeachersService
{
    /**
     * added optional int $userId to allow student librarians
     * access to their sponsoring teacher's co-teacher information.
     * @return array
     */
    public static function getCoTeachersIds(int $userId = 0): array
    {
        $teacherIds = [];
        if (!$userId) {
            $userId = auth()->id();
        }

        if (auth()->user()->isLibrarian()) {
            $userId = LibLibrarian::where('user_id', auth()->id())->first()->teacherUserId;
        }

        $teacher = Teacher::where('user_id', $userId)->first();
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

    public static function getCoTeachersSchoolIds(): array
    {
        $mySchools = Coteacher::query()
            ->where('teacher_id', auth()->id())
            ->pluck('school_id')
            ->toArray();

        $myCoTeachingSchools = Coteacher::query()
            ->where('coteacher_id', auth()->id())
            ->pluck('school_id')
            ->toArray();

        return array_unique(array_merge($mySchools, $myCoTeachingSchools));
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
