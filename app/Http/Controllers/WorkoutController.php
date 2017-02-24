<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\RoutineJunction;
use App\Routine;

class WorkoutController extends Controller
{
    public function selectWorkout ()
    {
    	$routines = Routine::where('user_id', Auth::id())
    	 	->get();
	 	$junctions = RoutineJunction::where('user_id', Auth::id())
	 		->get();

    	return view('workouts.selectWorkout', [
    		'routines'  => $routines,
    		'junctions' => $junctions
		]);
    }

    public function startWorkout (Routine $routine)
    {
    	$exercises = RoutineJunction::where('routine_id', $routine->id)
    		->get();

        // If gymming is in progres, do not reset sessions
        if (!session('gymming')) {
            session()->flush();
            foreach ($exercises as $exercise) {
                session()->put([
                      $exercise->exercise_name => $exercise->id
                ]);
            }
        }
        session(['gymming' => true]);

    	return view('workouts.startWorkout', [
    		'exercises' => $exercises
		]);
    }

    public function finishWorkout ()
    {
        return back()->with('success', 'Session saved!');
    }

    public function viewWorkouts ()
    {
    	
    }
}
