<?php

namespace App\Services;

use Illuminate\Support\Str;

class ConvertToPenniesService
{
    static public function usdToPennies($usd): int
    {
        if (!$usd) {
            return 0;
        }

        //remove $ if found
        if (Str::contains($usd, '$')) {
            $use = Str::remove($usd, '$');
        }

        return (int) ($usd * 100);
    }
}
