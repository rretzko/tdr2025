<?php

namespace App\Services;

class CalcSeniorYearService
{
    public function getSeniorYear(): int
    {
        $mth = (int) date('n');
        $yr = (int) date('Y');

        return ($mth > 6)
            ? $yr
            : ($yr + 1);
    }
}
