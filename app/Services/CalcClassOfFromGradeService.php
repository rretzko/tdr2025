<?php

namespace App\Services;

class CalcClassOfFromGradeService
{
    public function getClassOf(int $grade, int $srYear = 0)
    {
        if (!$srYear) {
            $service = new CalcSeniorYearService();
            $srYear = $service->getSeniorYear(); //2025
        }

        return ($srYear + (12 - $grade));
    }
}
