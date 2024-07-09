<?php

namespace App\Services;

class CoTeachersService
{
    /**
     * @return array
     * @todo process for identifying co-teachers remains to be sussed out.
     */
    public static function getCoTeachersIds(): array
    {
        return [auth()->id()];
    }
}
