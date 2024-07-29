<?php

namespace App\Services;

use App\Models\Schools\Teacher;
use App\Models\UserConfig;

class SetSchoolIdService
{
    public static function getSchoolId(): int
    {
        if (UserConfig::getValue('schoolId')) {

            return (int) UserConfig::getValue('schoolId');

        } else {

            //discover the first active school
            $schoolId = Teacher::query()
                ->where('user_id', auth()->id())
                ->first()
                ->schools
                ->where('active', '=', 1)
                ->first()
                ->id;

            //add the property to the UserConfig table
            UserConfig::setProperty('schoolId', $schoolId);
        }

        return SetSchoolIdService::getSchoolId();
    }
}
