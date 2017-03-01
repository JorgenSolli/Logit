<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\RoutineJunction;
use App\Workout;
use App\WorkoutJunction;
use DB;

class ApiController extends Controller
{
    public function getExercise ($exerciseId)
    {
        $routineId = RoutineJunction::where('id', $exerciseId)
            ->select('routine_id')
            ->get();

    	$exercise = RoutineJunction::where('id', $exerciseId)
    		->where('user_id', Auth::id())
    		->firstOrFail();

		$nrOfSets = $exercise->goal_sets;

		$returnHTML = view('workouts.exercise')
			->with('exercise', $exercise)
			->with('nrOfSets', $nrOfSets)
            ->with('routineId', $routineId)
			->render();
		return response()->json(array('success' => true, 'data'=>$returnHTML));
    }

    public function addExercise ($routine_id, Request $request)
    {
    	session()->forget($request->exercise_name);

        session()->push('exercises', [
            'exercise_name' => $request->exercise_name, 
                'exercises' => (
                    $request->exercise
                ),
        ]);

        return back()->with('success', 'Exercise saved. Good job!');
    }

    public function flushSessions ()
    {
        session()->flush();
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

            $result = collect([]);

            for ($i=1; $i <= $monthData[$selectedMonth]['days']; $i++) { 
                $result->put($i, [
                    'day' => $i,
                    'total' => 0
                ]);
            }

            $data = Workout::where('user_id', Auth::id())
                ->where(DB::raw('MONTH(created_at)'), '=', date($monthData[$selectedMonth]['int']))
                ->where(DB::raw('YEAR(created_at)'), '=', date($year))
                ->orderBy('created_at', 'ASC')
                ->get();

            foreach ($data as $value) {
                $day = $value->created_at->format('d');
                $result->put($day, ['total' => $result[$day]['total'] + 1]);
            }
        }

        $rtn = ([
            'data' => $result
        ]);

        return $rtn;
    }
}
