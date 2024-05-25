<?php

namespace App\ValueObjects;

use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;

class SchoolTeacherValueObject
{
    private static School $school;
    private static SchoolTeacher $schoolTeacher;
    private static Teacher $teacher;

    public static function getVo(SchoolTeacher $schoolTeacher): string
    {
        self::$school = School::find($schoolTeacher->school_id);
        self::$schoolTeacher = $schoolTeacher;
        self::$teacher = Teacher::find($schoolTeacher->teacher_id);

        return self::buildVo();
    }

    private static function buildVo(): string
    {
        $school =
        $str = '<ul>';

        $str = '<li>'.self::$school->name.'</li>';
        $str .= '<li>'.self::$school->city.' in '.self::$school->county->name.'. '.self::$school->postal_code.'</li>';
        $str .= '<li>Grades taught: '.self::$schoolTeacher->gradesTaughtCsv.'</li>';
        $str .= '<li>Grades I teach: '.self::$teacher->getGradesITeachCsv(self::$school).'</li>';
        $str .= '</ul>';

        return $str;
    }
}
