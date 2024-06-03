<?php

namespace App\Services;

class CalcClassOfFromGradeService
{
    public function getClassOf(int $grade)
    {
        $service = new CalcSeniorYearService();
        $srYear = $service->getSeniorYear(); //2025

        return ($srYear + (12 - $grade));
    }
}
