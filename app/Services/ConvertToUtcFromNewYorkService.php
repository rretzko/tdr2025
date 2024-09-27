<?php

namespace App\Services;

use Carbon\Carbon;

class ConvertToUtcFromNewYorkService
{
    /**
     * Convert an America/New_York $timestamp string to UTC timestamp string
     * @param  string  $timestamp
     * @return string
     */
    public static function convert(string $timestamp): string
    {
        $timestampInNewYork = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'America/New_York');

        return $timestampInNewYork->setTimezone('UTC');
    }
}
