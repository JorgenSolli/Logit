<?php
namespace Logit\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use Logit\Note;
use Logit\Workout;
use Logit\RoutineJunction;
use Logit\WorkoutJunction;

class ApiController extends Controller
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

    public function getExercise ($exerciseId)
    {
        $userId = Auth::id();

        $note = Note::where('routine_junction_id', $exerciseId)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();
            
        $routineId = RoutineJunction::where('id', $exerciseId)
            ->where('user_id', $userId)
            ->select('routine_id')
            ->get();

    	$exercise = RoutineJunction::where('id', $exerciseId)
    		->where('user_id', Auth::id())
    		->firstOrFail();

		$nrOfSets = $exercise->goal_sets;
            
        $previousExercise = WorkoutJunction::where('routine_id', $exercise->routine_id)
            ->where('exercise_name', $exercise->exercise_name)
            ->where('user_id', Auth::id())
            ->limit($nrOfSets)
            ->orderBy('created_at', 'DESC')
            ->get();

		$returnHTML = view('workouts.exercise')
			->with('exercise', $exercise)
            ->with('prevExercise', $previousExercise)
			->with('nrOfSets', $nrOfSets)
            ->with('routineId', $routineId)
            ->with('note', $note)
			->render();

		return response()->json(array('success' => true, 'data'=>$returnHTML));
    }
    
    public function addExercise ($routine_id, Request $request)
    {   

    	session()->forget($request->exercise_name);

        session()->push('exercises', [
            'exercise_name' => $request->exercise_name, 
            'note' => ([
                'text' => $request->note,
                'labelType' => $request->labelType
            ]),
            'routine_junction_id' => $request->routine_junction_id,
            'exercises' => (
                $request->exercise
            ),
        ]);

        return back()->with('success', 'Exercise saved. Good job!');
    }

    public function getWorkout ($workoutId)
    {
        $workout = WorkoutJunction::where('workout_id', $workoutId)
            ->where('user_id', Auth::id())
            ->get();

        $returnHTML = view('workouts.viewWorkout')
            ->with('workout', $workout)
            ->with('workoutId', $workoutId)
            ->render();
        return response()->json(array('success' => true, 'data'=>$returnHTML));
    }

    public function deleteWorkout (Workout $workout)
    {
        if ($workout->user_id == Auth::id()) {
            WorkoutJunction::where('workout_id', $workout->id)
                ->delete();
            $workout->delete();

            return response()->json(array('success' => true));
        }
    }

    public function updateWorkout (Request $request, Workout $workout)
    {
        if ($workout->user_id == Auth::id()) {

            WorkoutJunction::where([
                    ['user_id', '=', Auth::id()],
                    ['id', '=', $request->junction_id]
                ])
                ->update([
                    'weight' => $request->weight,
                    'reps' => $request->reps
                ]);

            return response()->json(array('success' => true));
        }
    }
    
    public function flushSessions ()
    {
        session()->forget('exercises');
        session()->forget('gymming');
        return redirect('/dashboard/start/')->with('success', 'Workout successfully stopped');
    }

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
            $result = collect([
                0  => ['month' => 'Jan', 'total' => 0], 
                1  => ['month' => 'Feb', 'total' => 0],
                2  => ['month' => 'Mar', 'total' => 0],
                3  => ['month' => 'Apr', 'total' => 0],
                4  => ['month' => 'May', 'total' => 0],
                5  => ['month' => 'Jun', 'total' => 0],
                6  => ['month' => 'Jul', 'total' => 0],
                7  => ['month' => 'Aug', 'total' => 0],
                8  => ['month' => 'Sep', 'total' => 0],
                9  => ['month' => 'Oct', 'total' => 0],
                10 => ['month' => 'Nov', 'total' => 0],
                11 => ['month' => 'Dec', 'total' => 0]
            ]);
            for ($i=0; $i < count($data); $i++) {
                $month = $data[$i]->created_at->format('M');
                $result->put($getMonth[$month], [
                    'month' => $month,
                    'total' => $result[$getMonth[$month]]['total'] + 1
                ]);
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

            $result = array(
                'labels' => [],
                'series' => [
                    []
                ],
                'max' => 0,
            );

            for ($i=1; $i <= $monthData[$selectedMonth]['days']; $i++) { 
                array_push($result['labels'], $i -1);
                array_push($result['series'][0], 0);
            }


            $data = Workout::where('user_id', Auth::id())
                ->where(DB::raw('MONTH(created_at)'), '=', date($monthData[$selectedMonth]['int']))
                ->where(DB::raw('YEAR(created_at)'), '=', date($year))
                ->orderBy('created_at', 'ASC')
                ->get();

            foreach ($data as $value) {
                $day = $value->created_at->format('d');
                
                // Removes a zero in front of the int.
                if ($day > 0 && $day < 10) {
                    $day = ltrim($day, 0);
                }

                // $prev = $result['series'][0][(int)$day];
                $result['series'][0][(int)$day] = $result['series'][0][(int)$day] + 1;
            }

            $result['max'] = max($result['series'][0]) + 1;
        }

        return $result;
    }

    public function getAvgGymTime ($type, $year, $month)
    {   


        if ($type == "year") {
            // ...
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

    public function getMusclegroups ($type, $year, $month)
    {

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
            // ...
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

            $data = WorkoutJunction::where([
                    ['workout_junctions.user_id', Auth::id()],
                    [DB::raw('MONTH(workout_junctions.created_at)'), '=', date($monthData[$selectedMonth]['int'])],
                    [DB::raw('YEAR(workout_junctions.created_at)'), '=', date($year)],
                    ['workout_junctions.set_nr', '=', 1], // Only count the first set of the exercise!
                ])
                ->join('routine_junctions', 'workout_junctions.exercise_name', '=', 'routine_junctions.exercise_name')
                ->get();

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
                $index = array_search($key,array_keys($musclegroups));
                $percent = $val / $total * 100;

                $result['series'][$index] = $percent;
            }

        }

        return $result;
    }


}