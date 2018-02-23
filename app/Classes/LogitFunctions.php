<?php

namespace Logit\Classes;

use DB;
use Carbon;

use Logit\User;
use Logit\Note;
use Logit\Friend;
use Logit\Workout;
use Logit\Settings;
use Logit\LatestActivity;
use Logit\WorkoutJunction;
use Logit\RoutineJunction;

use Illuminate\Support\Facades\Auth;


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
        LogitFunctions::canView($userId);

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

    public static function fetchSessionData ($type, $month, $year, $userId, $metaUser = false)
    {
        LogitFunctions::canView($userId);

        if ($type === "year") {
            $data = Workout::where('user_id', $userId)
                ->where( DB::raw('YEAR(created_at)'), '=', date($year) )
                ->get();

            $getMonth = LogitFunctions::parseDate($type, $year, $month);

            $result = array(
                'labels' => [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                'series' => [],
                'max' => 0,
                'stepSize' => 5,
            );

            # Populates the series index with months in the year
            for ($i=0; $i < 12; $i++) {
                array_push($result['series'], 0);
            }

            # Iterates over our results and pushes the data into our array
            for ($i=0; $i < count($data); $i++) {
                // Gets the month of the current results and formats in the format specified in our getMonth array so we can match the results
                $month = $data[$i]->created_at->format('M');
                // Populates the series array. Using getMonth to get the correct index for the month
                $result['series'][$getMonth[$month]] = $result['series'][$getMonth[$month]] + 1;
            }

            # Finds the max value and appends 1 (for cosmetic reason)
            $result['max'] = max($result['series']) + 1;
        }
        elseif ($type == "months") {
            # Makes sure the month we get from out request is formated correctly
            $selectedMonth = ucfirst($month);

            # Sets up the expected dataformat
            $monthData = LogitFunctions::parseDate($type, $year, $month);

            # Initialized our output
            $result = array(
                'labels' => [],
                'series' => [],
                'meta' => [],
                'max' => 0,
                'stepSize' => 1,
            );

            # Populates the outputArray with data specific for the specific month
            for ($i=1; $i <= $monthData[$selectedMonth]['days']; $i++) {
                array_push($result['labels'], $i);
                array_push($result['series'], 0);
                array_push($result['meta'], "");
            }

            # Grabs the data relevant
            $data = Workout::where('workouts.user_id', $userId)
                ->where(DB::raw('MONTH(workouts.created_at)'), '=', date($monthData[$selectedMonth]['int']))
                ->where(DB::raw('YEAR(workouts.created_at)'), '=', date($year))
                ->join('routines', 'workouts.routine_id', '=', 'routines.id')
                ->orderBy('workouts.created_at', 'ASC')
                ->select('workouts.created_at', 'routines.routine_name')
                ->get();
            # Iterates over the result
            foreach ($data as $value) {
                $day = $value->created_at->format('d');

                # Removes a zero in front of the int. 04 becomes 4 and so on. This is so we can corretctly match indexes in our result array
                if ($day > 0 && $day < 10) {
                    $day = ltrim($day, 0);
                }

                # Subtracts 1 on the index for day, as this is naturally offset by this amount. Index starts at 0, day starts at 1
                $result['series'][(int)$day - 1] = $result['series'][(int)$day - 1] + 1;

                $user = "";
                if ($metaUser) {
                    $user = "You: ";
                    if ($userId !== Auth::id()) {
                        $user = User::where('id', $userId)->first()->name . ": ";
                    }
                }

                $string = $user . $value->routine_name;
                if ($result['meta'][(int)$day - 1] != "") {
                    $comma = ", ";
                    $string = $result['meta'][(int)$day - 1] .= $comma .= $string;
                }

                $result['meta'][(int)$day - 1] = $string;
            }

            # Finds the max value and appends 1 (for cosmetic reason)
            $max = 0;
            foreach ($result['series'] as $value) {
                if ($value > $max - 1) {
                    $max = $value + 0.1;
                }
            }

            $result['max'] = $max;
        }

        # Returns the result as an json array
        return $result;
    }

    public static function is_leap_year ($year)
    {
        if ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0))) {
            return 29;
        }
        return 28;
    }

    public static function parseMinutes ($minutes, $format = '%02d:%02d')
    {
        if ($minutes < 1) {
            return;
        }
        $hours = floor($minutes / 60);
        $minutes = ($minutes % 60);

        return sprintf($format, $hours, $minutes);
    }

    public static function parseRestTime ($time, $format = '%02d:%02d')
    {
        $minutes = floor($time);
        $seconds = round(60*($time-$minutes));

        return sprintf($format, $minutes, $seconds);
    }

    public static function canView ($userId)
    {
        if ($userId !== Auth::id()) {
            $friends = Friend::where([
                ['user_id', Auth::id()],
                ['friends_with', $userId],
                ['pending', 0]
            ])->first();


            if (!$friends) {
                abort(403, "You don't have permission to view this page");
            }
        }

        return true;
    }

    /**
     * User choses one exercise and will be able to see the progress in specified timeframe
     *
     * @param  string $type specifies year or month
     * @param  int $year specifies the year
     * @param  int $month specifies the mont
     * @param  boolean $activeExercises specifies to fetch all or just active excercises
     * @param  boolean @isTopTen specifies to fetch only top ten or not
     *
     * @return \Illuminate\Http\Response
     */
    public static function getExercises ($type, $year, $month, $activeExercises, $isTopTen, $userId)
    {

        $limit = $isTopTen ? 10 : 9999;
        $show_active_exercises = $activeExercises ? ['routines.active', 1] : ['routines.active', '>=', 0];

        LogitFunctions::canView($userId);

        $settings = Settings::where('user_id', $userId)->first();
        $brukerinfo = User::where('id', $userId)->first();

        if (!LogitFunctions::hasWorkoutData($userId)) {
            return null;
        }

        if ($type == "year") {
            if ($settings->count_warmup_in_stats == 1) {
                $where = [
                    ['workout_junctions.user_id', $userId],
                    [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                ];
            }
            else {
                $where = [
                    ['workout_junctions.user_id', $userId],
                    ['is_warmup', 0],
                    [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                ];
            }
        }
        else {
            $selectedMonth = ucfirst($month);
            $monthData = LogitFunctions::parseDate($type, $year, $month);

            if ($settings->count_warmup_in_stats == 1) {
                $where = [
                    ['workout_junctions.user_id', $userId],
                    [DB::raw('MONTH(workout_junctions.created_at)'), '=', date($monthData[$selectedMonth]['int'])],
                    [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                ];
            }
            else {
                $where = [
                    ['workout_junctions.user_id', $userId],
                    ['is_warmup', 0],
                    [DB::raw('MONTH(workout_junctions.created_at)'), '=', date($monthData[$selectedMonth]['int'])],
                    [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                ];
            }
        }

        $topExercises = WorkoutJunction::select(DB::raw('workout_junctions.id, workout_junctions.exercise_name, count(*) as count'))
            ->join('routines', 'workout_junctions.routine_id', '=', 'routines.id')
            ->where($where)
            ->groupBy('exercise_name')
            ->having('count', '>', 0)
            ->orderBy('count', 'DESC')
            ->limit($limit)
            ->get();

        return $topExercises;
    }

    public static function getNote ($exerciseId, $markSeen)
    {
        $userId = Auth::id();
        $settings = Settings::where('user_id', Auth::id())->first();

        $routine = RoutineJunction::where('id', $exerciseId)
            ->where('user_id', $userId)
            ->select('type', 'exercise_name')
            ->first();

        $note = false;

        if ($settings->strict_notes) {
            $note = Note::where('routine_junction_id', $exerciseId)
                ->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->first();
        } else {
            $note = Note::where([
                    ['user_id', $userId],
                    ['exercise_name', $routine->exercise_name],
                ])
                ->orderBy('created_at', 'DESC')
                ->first();
        }

        if ($markSeen && $note) {
            $note->is_seen = 1;
            return $note->save();

        } else {
            return $note;
        }
    }

    public static function setActivity ($activityType, $activity)
    {
        $la = new LatestActivity;
        $la->user_id = Auth::id();
        $la->activity_type = $activityType;
        $la->activity = $activity;
        $la->save();
    }

    public static function hasWorkoutData ($userId)
    {
        return (bool) Workout::where('user_id', $userId)->first();
    }
}