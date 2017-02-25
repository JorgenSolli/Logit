<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\RoutineJunction;
use App\Workout;
use App\WorkoutJunction;

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

		$workout = new Workout;
        $exercise->routine_id = $routine_id;
		$workout->user_id = Auth::id();
		$workout->save();

    	foreach ($request->exercise as $value) {
    		$exercise = new WorkoutJunction;

    		$exercise->workout_id 		= $workout->id;
            $exercise->routine_id       = $routine_id;
    		$exercise->exercise_name 	= $request->exercise_name;
    		$exercise->reps 			= $value['reps'];
    		$exercise->set_nr 			= $value['set'];

    		$exercise->save();
    	}
    	return back()->with('success', 'Exercise saved. Good job!');
    }

    public function flushSessions ()
    {
        session()->flush();
        return back()->with('success', 'Workout successfully stopped');
    }
}
