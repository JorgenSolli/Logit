<?php

namespace Logit\Classes;

class LogitFunctions {

	/**
     * Computes the date-logic for all dashboard-statistic methods
     *
     * @param  string $type specifies year or month
     * @param  int $year specifies the year
     * @param  int $month specifies the mont
     * @return array
     */
    public static function parseDate ($type, $year, $month)
    {
        if ($type === "year") {
            $getMonth = [
                'Jan' => 0,
                'Feb' => 1,
                'Mar' => 2,
                'Apr' => 3,
                'May' => 4,
                'Jun' => 5,
                'Jul' => 6,
                'Aug' => 7,
                'Sep' => 8,
                'Oct' => 9,
                'Nov' => 10,
                'Dec' => 11
            ];

            return $getMonth;
        }
        else {
            function is_leap_year($year) {
                if ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0))) {
                    return 29;
                }
                return 28;
            }

            # Sets up the expected dataformat
            $monthData = collect([
                'Jan' => [
                    'int' => 1,
                    'days' => 31
                ],
                'Feb' => [
                    'int' => 2,
                    'days' => is_leap_year($year)
                ],
                'Mar' => [
                    'int' => 3,
                    'days' => 31
                ],
                'Apr' => [
                    'int' => 4,
                    'days' => 30
                ],
                'May' => [
                    'int' => 5,
                    'days' => 31
                ],
                'Jun' => [
                    'int' => 6,
                    'days' => 30
                ],
                'Jul' => [
                    'int' => 7,
                    'days' => 31
                ],
                'Aug' => [
                    'int' => 8,
                    'days' => 31
                ],
                'Sep' => [
                    'int' => 9,
                    'days' => 30
                ],
                'Oct' => [
                    'int' => 10,
                    'days' => 31
                ],
                'Nov' => [
                    'int' => 11,
                    'days' => 30
                ],
                'Dec' => [
                    'int' => 12,
                    'days' => 31
                ]
            ]);

            return $monthData;
        }
    }

    public static function parseMinutes ($minutes, $format = '%02d:%02d') {
        if ($minutes < 1) {
            return;
        }
        $hours = floor($minutes / 60);
        $minutes = ($minutes % 60);

        return sprintf($format, $hours, $minutes);
    }

    public static function parseRestTime ($time, $format = '%02d:%02d') {
        $minutes = floor($time);
        $seconds = round(60*($time-$minutes));

        return sprintf($format, $minutes, $seconds);
    }
}