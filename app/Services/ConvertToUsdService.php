<?php

namespace App\Services;

class ConvertToUsdService
{
    static public function penniesToUsd($pennies): string
    {
        //early exit
        if (!$pennies) {
            return 0;
        }

        return number_format(($pennies / 100), 2);
    }
}
