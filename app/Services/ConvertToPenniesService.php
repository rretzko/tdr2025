<?php

namespace App\Services;

class ConvertToPenniesService
{
    static public function usdToPennies($usd): int
    {
        if (!$usd) {
            return 0;
        }

        return (int) ($usd * 100);
    }
}
