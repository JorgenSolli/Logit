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
    	$exercise = RoutineJunction::where('id', $exerciseId)
    		->where('user_id', Auth::id())
    		->firstOrFail();

		$nrOfSets = $exercise->goal_sets;

		$returnHTML = view('workouts.exercise')
			->with('exercise', $exercise)
			->with('nrOfSets', $nrOfSets)
			->render();
		return response()->json(array('success' => true, 'data'=>$returnHTML));
    }

    public function addExercise (Request $request)
    {
    	session()->forget($request->exercise_name);

		$workout = new Workout;
		$workout->user_id = Auth::id();
		$workout->save();

    	foreach ($request->exercise as $value) {
    		$exercise = new WorkoutJunction;

    		$exercise->workout_id 		= $workout->id;
    		$exercise->exercise_name 	= $request->exercise_name;
    		$exercise->reps 			= $value['reps'];
    		$exercise->set_nr 			= $value['set'];

    		$exercise->save();
    	}
    	return back()->with('success', 'Exercise saved. Good job!');
    }
}
