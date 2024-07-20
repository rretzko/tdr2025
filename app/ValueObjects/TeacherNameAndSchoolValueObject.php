<?php

namespace App\ValueObjects;

use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;

class TeacherNameAndSchoolValueObject
{
    public static function getVo(Teacher $teacher): string
    {
        $str = $teacher->user->last_name.', ';
        $str .= $teacher->user->first_name;
        $str .= $teacher->user->middle_name ?: '';
        $str .= ' (';
        foreach ($teacher->schools as $school) {

            $str .= $school->name.',';
        }
        $str = rtrim($str, ","); //remove trailing comma
        $str .= ')';

        return $str;
    }

}
