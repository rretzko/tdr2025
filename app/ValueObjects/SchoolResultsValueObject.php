<?php

namespace App\ValueObjects;

use App\Models\Schools\School;

class SchoolResultsValueObject
{
    public static function getVo(School $school): string
    {
        return $school->name
            .' ('
            .$school->city
            .' in '
            .$school->countyName.', '
            .$school->geostateAbbr.'  '
            .$school->postal_code.')';
    }
}
