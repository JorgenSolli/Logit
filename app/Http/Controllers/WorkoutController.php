<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\RoutineJunction;
use App\WorkoutJunction;
use App\Routine;
use App\Workout;
use App\Note;

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
        if (session('gymming') != $routine->id) {
            session()->flush();
            foreach ($exercises as $exercise) {
                session()->put([
                      $exercise->exercise_name => $exercise->id
                ]);
            }
            session(['gymming' => $routine->id]);
        }

    	return view('workouts.startWorkout', [
    		'exercises'  => $exercises,
            'routine_id' => $routine->id
		]);
    }

    public function finishWorkout ($routine_id)
    {
        $session = session('exercises');
        // session()->forget('exercises');
        // session()->forget('gymming');
        
        $user_id = Auth::id();

        $workout = new Workout;
        $workout->routine_id = $routine_id;
        $workout->user_id = $user_id;
        $workout->save();

        foreach ($session as $session_exercise) {
            $exercise_name = $session_exercise['exercise_name'];
            foreach ($session_exercise['exercises'] as $exercise_specific) {
                $exercise = new WorkoutJunction;
                $exercise->workout_id       = $workout->id;
                $exercise->user_id          = $user_id;
                $exercise->routine_id       = $routine_id;
                $exercise->exercise_name    = $exercise_name;
                $exercise->reps             = $exercise_specific['reps'];
                $exercise->set_nr           = $exercise_specific['set'];
                $exercise->weight           = $exercise_specific['weight'];

                $exercise->save();
            }

            $note = new Note;
            $note->user_id              = $user_id;
            $note->workout_junction_id  = $exercise->id;
            $note->note                 = $session_exercise['note'];
            $note->save();

        }
        
        return redirect('/dashboard/workouts')->with('success', 'Workout saved. Good job!');
    }

    public function viewWorkouts (Workout $workout)
    {
    	$workouts = Workout::where('workouts.user_id', Auth::id())
            ->join('routines', 'workouts.routine_id', '=', 'routines.id')
            ->select('workouts.id AS workout_id', 'workouts.routine_id', 'workouts.created_at', 'workouts.updated_at', 'routines.routine_name')
            ->orderBy('workouts.created_at', 'DESC')
            ->get();

        return view('workouts.myWorkouts', [
            'workouts' => $workouts
        ]);
    }
}
