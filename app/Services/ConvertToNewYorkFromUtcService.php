<?php

namespace App\Services;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;

class ConvertToNewYorkFromUtcService
{
    /**
     * Convert an America/New_York UNIX $timestamp string to America/New_York datetime string
     * ex. $timestamp = "1732158000";
     * @param  string  $timestamp
     * @return string //ex. 2024-11-20 17:00:00
     */
    public static function convert(string $timestamp): string
    {
        // Create a DateTime object from the timestamp
        $date = new DateTime("@$timestamp", new DateTimeZone('America/New_York'));
//dd($date);
        // Set the timezone to UTC (since the timestamp is in UTC)
        $date->setTimezone(new DateTimeZone('America/New_York'));
//        dd($date);
        // Change the timezone to America/New_York
        $date->setTimezone(new DateTimeZone('America/New_York'));

        // Return the formatted date
        return $date->format('Y-m-d H:i:s');
    }
}
