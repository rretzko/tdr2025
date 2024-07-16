<?php

namespace App\Services;

class ConvertToPenniesService
{
    static public function usdToPennies($usd): int
    {
        return (int) ($usd * 100);
    }
}
