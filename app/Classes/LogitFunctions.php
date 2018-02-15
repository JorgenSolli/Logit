<?php

namespace Logit\Classes;

use DB;
use Carbon;

use Logit\Workout;

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
            # Sets up the expected dataformat
            $monthData = collect([
                'Jan' => [
                    'int' => 1,
                    'days' => 31
                ],
                'Feb' => [
                    'int' => 2,
                    'days' => LogitFunctions::is_leap_year($year)
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

    /**
     * User choses one exercise and will be able to see the progress in specified timeframe
     *
     * @param  string $type specifies year or month
     * @param  int $year specifies the year
     * @param  int $month specifies the mont
     * @param  string $exercise name of exercise to compare
     * @return \Illuminate\Http\Response
     */
    public static function fetchExerciseData ($type, $month, $year, $exercise, $userId)
    {
        $result = array(
            'labels' => [],
            'series' => [
                [],
                []
            ],
            'exercise' => null,
            'success' => true,
            'max' => 0,
        );
        if ($type == "year") {
            $workouts = Workout::with(['junction' => function($query) use ($exercise, $year, $userId) {
                    $query->where([
                        ['exercise_name', 'like', $exercise],
                        ['workout_junctions.user_id', $userId],
                        [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                    ]);
                }])
            ->get();
        }
        else {
            # Makes sure the month we get from out request is formated correctly
            $selectedMonth = ucfirst($month);
            # Sets up the expected dataformat
            $monthData = LogitFunctions::parseDate($type, $year, $month);

            $workouts = Workout::with(['junction' => function($query) use ($exercise, $year, $monthData, $selectedMonth, $userId) {
                    $query->where([
                        ['exercise_name', 'like', $exercise],
                        [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                        [DB::raw('MONTH(created_at)'), '=', date($monthData[$selectedMonth]['int'])],
                        ['workout_junctions.user_id', $userId]
                    ]);
                }])
            ->get();
        }   
        
        foreach ($workouts as $workout) {
            if ($workout->junction) {
                foreach ($workout->junction as $junction) {
                    array_push($result['labels'], Carbon\Carbon::parse($junction->created_at)->format('d/m'));

                    $weight = 0;
                    if ($junction->weight_type === "assisted") {
                        $weight = -1 * $junction->weight;
                    } else {
                        $weight = $junction->weight;
                    }
                    array_push($result['series'][0], $weight);

                    array_push($result['series'][1], $junction->reps);
                }
            }
        }

        $max = 0;
        foreach ($result['series'] as $series) {
            foreach ($series as $value) {
                if ($value > $max - 1) {
                    $max = $value + 10;
                }  
            }
        }

        $result['max'] = $max;

        if (isset($result['series'][0]) || isset($result['series'][1])) {
            $result['exercise'] = $exercise;
        }
        else {
            $result['success'] = false;
        }
        return $result; 
    }

    public static function is_leap_year ($year) {
        if ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0))) {
            return 29;
        }
        return 28;
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