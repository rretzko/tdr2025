<?php

namespace App\Services;

use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;

class MissingGradesService
{
    public static function missingGrades(): bool
    {
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $schoolId = UserConfig::getValue('schoolId');
        $school = School::find($schoolId);

        //array
        $gradesCount = count($school->grades);
        //Collection
        $gradesITeachCount = GradesITeach::query()
            ->where('school_id', $schoolId)
            ->where('teacher_id', $teacher->id)
            ->get()
            ->count();

        //bool true if either $grades or $gradesITeach are 0;
        return !($gradesCount && $gradesITeachCount);
    }
}
