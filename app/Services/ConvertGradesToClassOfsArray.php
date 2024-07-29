<?php

namespace App\Services;

class ConvertGradesToClassOfsArray
{

    public static function convertGrades(array $grades): array
    {
        $a = [];

        $service = new CalcClassOfFromGradeService();

        foreach ($grades as $grade) {

            $a[] = $service->getClassOf((int) $grade);
        }

        return $a;
    }

}
