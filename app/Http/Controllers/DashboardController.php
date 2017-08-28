<?php

namespace Logit\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use Logit\User;
use Logit\Workout;
use Logit\Settings;
use Logit\WorkoutJunction;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timezone');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $brukerinfo = Auth::user();
        $topNav = [
            0 => [
                'url'  => '/',
                'name' => 'Dashboard'
            ]
        ];

        $firstTime = false;

        /* Checks if this is the users first time visiting */
        if ($brukerinfo->first_time === 1) {
            /* Setting some standard settings */
            Settings::create([
                'user_id'        => Auth::id(),
                'timezone'       => 'UTC',
                'unit'           => 'Metric',
                'recap'          => 1,
                'share_workouts' => 0,
                'accept_friends' => 0,
            ]);

            /* Letting the user know about some stuff */
            $firstTime = true;

            $brukerinfo->update(['first_time'=> 0]);
        }
        

        $topTenExercises = WorkoutJunction::select(DB::raw('id, exercise_name, count(*) as c'))
            ->where([
                ['user_id', $brukerinfo->id],
                ['is_warmup', 0],
            ])
            ->groupBy('exercise_name')
            ->having('c', '>', 1)
            ->orderBy('c', 'DESC')
            ->limit(10)
            ->get();
            
        return view('dashboard', [
            'topNav'          => $topNav,
            'brukerinfo'      => $brukerinfo,
            'firstTime'       => $firstTime,
            'topTenExercises' => $topTenExercises,
        ]);
    }

    /**
     * Gets the workout activity for the linechart
     *
     * @param  string $type specifies year or month
     * @param  int $year specifies the year
     * @param  int $month specifies the mont
     * @return \Illuminate\Http\Response
     */
    public function getGrapData ($type, $year, $month)
    {
        if ($type == "year") {
            $data = Workout::where('user_id', Auth::id())
                ->where( DB::raw('YEAR(created_at)'), '=', date($year) )
                ->get();

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
                'series' => [
                    []
                ],
                'max' => 0,
            );

            # Populates the series index with months in the year
            for ($i=0; $i < 12; $i++) { 
                array_push($result['series'][0], 0);
            }

            # Iterates over our results and pushes the data into our array
            for ($i=0; $i < count($data); $i++) {
                // Gets the month of the current results and formats in the format specified in our getMonth array so we can match the results
                $month = $data[$i]->created_at->format('M');
                // Populates the series array. Using getMonth to get the correct index for the month
                $result['series'][0][$getMonth[$month]] = $result['series'][0][$getMonth[$month]] + 1;
            }

            # Finds the max value and appends 1 (for cosmetic reason)
            $result['max'] = max($result['series'][0]) + 1;
        } 
        elseif ($type == "months") {
            # Function to fid leap year. This will affect our results
            function is_leap_year($year) {
                if ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0))) {
                    return 29;
                }
                return 28;
            }
            # Makes sure the month we get from out request is formated correctly
            $selectedMonth = ucfirst($month);
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
                    'int' => 1,
                    'days' => 31
                ],
                'Nov' => [
                    'int' => 1,
                    'days' => 30
                ],
                'Dec' => [
                    'int' => 1,
                    'days' => 31
                ] 
            ]);

            # Initialized our output
            $result = array(
                'labels' => [],
                'series' => [
                    []
                ],
                'max' => 0,
            );

            # Populates the outputArray with data specific for the specific month
            for ($i=1; $i <= $monthData[$selectedMonth]['days']; $i++) { 
                array_push($result['labels'], $i);
                array_push($result['series'][0], 0);
            }

            # Grabs the data relevant
            $data = Workout::where('user_id', Auth::id())
                ->where(DB::raw('MONTH(created_at)'), '=', date($monthData[$selectedMonth]['int']))
                ->where(DB::raw('YEAR(created_at)'), '=', date($year))
                ->orderBy('created_at', 'ASC')
                ->get();

            # Iterates over the result
            foreach ($data as $value) {
                $day = $value->created_at->format('d');
                
                # Removes a zero in front of the int. 04 becomes 4 and so on. This is so we can corretctly match indexes in our result array
                if ($day > 0 && $day < 10) {
                    $day = ltrim($day, 0);
                }

                # Subtracts 1 on the index for day, as this is naturally offset by this amount. Index starts at 0, day starts at 1
                $result['series'][0][(int)$day - 1] = $result['series'][0][(int)$day - 1] + 1;
            }

            # Finds the max value and appends 1 (for cosmetic reason)
            $result['max'] = max($result['series'][0]) + 1;
        }

        # Returns the result as an json array
        return $result;
    }

    /**
     * Gets the average workout time
     *
     * @param  string $type specifies year or month
     * @param  int $year specifies the year
     * @param  int $month specifies the mont
     * @return \Illuminate\Http\Response
     */
    public function getAvgGymTime ($type, $year, $month)
    {   

        if ($type == "year") {
            # Grabs the data for the specified year
            $data = Workout::where('user_id', Auth::id())
                ->where(DB::raw('YEAR(created_at)'), '=', date($year))
                ->where('duration_minutes', '>', 10)
                ->avg('duration_minutes');

            # Finds hours by dividing my 60 and flooring the results
            $hours = floor($data / 60);
            if ($hours < 10) {
                # Formats the results. Any hours below 10 will have a zero appended in front. So 03, not 3. Looks better
                $hours = sprintf("%02d", $hours);
            }

            # Gets minutes
            $minutes = ($data % 60);
            if ($minutes < 10) {
                # Formats the results. Any hours below 10 will have a zero appended in front. So 03, not 3. Looks better
                $minutes = sprintf("%02d", $minutes);
            }
        } 
        elseif ($type == "months") {
            function is_leap_year($year) {
                if ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0))) {
                    return 29;
                }
                return 28;
            }
            $selectedMonth = ucfirst($month);
            $isLeapYear = false;
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
                    'int' => 1,
                    'days' => 31
                ],
                'Nov' => [
                    'int' => 1,
                    'days' => 30
                ],
                'Dec' => [
                    'int' => 1,
                    'days' => 31
                ] 
            ]);

            $data = Workout::where('user_id', Auth::id())
                ->where(DB::raw('MONTH(created_at)'), '=', date($monthData[$selectedMonth]['int']))
                ->where(DB::raw('YEAR(created_at)'), '=', date($year))
                ->where('duration_minutes', '>', 10)
                ->avg('duration_minutes');

            $hours = floor($data / 60);
            if ($hours < 10) {
                $hours = sprintf("%02d", $hours);
            }

            $minutes = ($data % 60);
            if ($minutes < 10) {
                $minutes = sprintf("%02d", $minutes);
            }
        }

        return response()->json(
            array(
                'avg_hr' => $hours,
                'avg_min' => $minutes
                )
            );
    }

    /**
     * Gets the percentage of musclegroups worked out in a specific timeframe
     *
     * @param  string $type specifies year or month
     * @param  int $year specifies the year
     * @param  int $month specifies the mont
     * @return \Illuminate\Http\Response
     */
    public function getMusclegroups ($type, $year, $month)
    {
        $settings = Settings::where('user_id', Auth::id())->first();

        $musclegroups = [
            'back' => 0, 
            'biceps' => 0, 
            'triceps' => 0, 
            'abs' => 0, 
            'shoulders' => 0, 
            'legs' => 0, 
            'chest' => 0
        ];

        $result = array(
            'labels' => [
                'back',
                'biceps',
                'triceps',
                'abs',
                'shoulders',
                'legs',
                'chest',
            ],
            'series' => [
                0,  // back
                0,  // biceps
                0,  // triceps
                0,  // abs
                0,  // shoulders
                0,  // legs
                0   // chest
            ],
        );

        if ($type == "year") {
            if ($settings->count_warmup_in_stats == 1) {

                $data = WorkoutJunction::where([
                        ['workout_junctions.user_id', Auth::id()],
                        [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                        ['workout_junctions.set_nr', '=', 1], // Only count the first set of the exercise!
                    ])
                    ->leftJoin('routine_junctions', 'workout_junctions.exercise_name', '=', 'routine_junctions.exercise_name')
                    ->select('workout_junctions.id', 'workout_junctions.routine_id', 'workout_junctions.workout_id', 'muscle_group')
                    ->get();

            }
            else {
                $data = WorkoutJunction::where([
                        ['workout_junctions.user_id', Auth::id()],
                        [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                        ['workout_junctions.set_nr', '=', 1], // Only count the first set of the exercise!
                        ['routine_junctions.is_warmup', '=', 0] // user does not want to count warmup sets.
                    ])
                    ->leftJoin('routine_junctions', 'workout_junctions.exercise_name', '=', 'routine_junctions.exercise_name')
                    ->select('workout_junctions.id', 'workout_junctions.routine_id', 'workout_junctions.workout_id', 'muscle_group')
                    ->get();
            }
            $data = $data->unique();

            $total = $data->count();
            foreach ($data as $mg) {
                /*
                 * Iterates throught results and pushes each musclegroup to the array
                 * Takes previous data and appends 1
                 */
                $musclegroups[$mg->muscle_group] = $musclegroups[$mg->muscle_group] + 1;
            }

            /* Iterates throught the total resunts and gets the actual percentage based on $total */
            foreach ($musclegroups as $key => $val) {
                if ($val > 0) {
                    $index = array_search($key,array_keys($musclegroups));
                    $percent = $val / $total * 100;

                    $result['series'][$index] = $percent;
                }
            }
        } 
        elseif ($type == "months") {
            function is_leap_year($year) {
                if ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0))) {
                    return 29;
                }
                return 28;
            }
            $selectedMonth = ucfirst($month);
            $isLeapYear = false;
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
                    'int' => 1,
                    'days' => 31
                ],
                'Nov' => [
                    'int' => 1,
                    'days' => 30
                ],
                'Dec' => [
                    'int' => 1,
                    'days' => 31
                ] 
            ]);

            if ($settings->count_warmup_in_stats == 1) {

                $data = WorkoutJunction::where([
                        ['workout_junctions.user_id', Auth::id()],
                        [DB::raw('MONTH(workout_junctions.created_at)'), '=', date($monthData[$selectedMonth]['int'])],
                        [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                        ['workout_junctions.set_nr', '=', 1], // Only count the first set of the exercise!
                    ])
                    ->leftJoin('routine_junctions', 'workout_junctions.exercise_name', '=', 'routine_junctions.exercise_name')
                    ->select('workout_junctions.id', 'workout_junctions.routine_id', 'workout_junctions.workout_id', 'muscle_group')
                    ->get();

            }
            else {
                $data = WorkoutJunction::where([
                        ['workout_junctions.user_id', Auth::id()],
                        [DB::raw('MONTH(workout_junctions.created_at)'), '=', date($monthData[$selectedMonth]['int'])],
                        [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                        ['workout_junctions.set_nr', '=', 1], // Only count the first set of the exercise!
                        ['routine_junctions.is_warmup', '=', 0] // user does not want to count warmup sets.
                    ])
                    ->leftJoin('routine_junctions', 'workout_junctions.exercise_name', '=', 'routine_junctions.exercise_name')
                    ->select('workout_junctions.id', 'workout_junctions.routine_id', 'workout_junctions.workout_id', 'muscle_group')
                    ->get();
            }
            $data = $data->unique();

            $total = $data->count();
            foreach ($data as $mg) {
                /*
                 * Iterates throught results and pushes each musclegroup to the array
                 * Takes previous data and appends 1
                 */
                $musclegroups[$mg->muscle_group] = $musclegroups[$mg->muscle_group] + 1;
            }

            /* Iterates throught the total resunts and gets the actual percentage based on $total */
            foreach ($musclegroups as $key => $val) {
                if ($val > 0) {
                    $index = array_search($key,array_keys($musclegroups));
                    $percent = $val / $total * 100;

                    $result['series'][$index] = $percent;
                }
            }
        }

        return $result;
    }  
}