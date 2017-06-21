<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\RoutineJunction;
use App\WorkoutJunction;
use App\Routine;
use App\Workout;
use App\Note;
use App\User;

class WorkoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timezone');
    }

    public function selectWorkout ()
    {
    	$routines = Routine::where('user_id', Auth::id())
    	 	->get();
	 	$junctions = RoutineJunction::where('user_id', Auth::id())
	 		->get();
        $nrInactive = Routine::where([
                ['user_id', '=', Auth::id()],
                ['active', '=', 0],
            ])
            ->count();

        $brukerinfo = Auth::user();

        $topNav = [
            0 => [
                'url'  => '/dashboard/start',
                'name' => 'Start Workout'
            ]
        ];

        return view('workouts.selectWorkout', [
            'topNav'     => $topNav,
            'routines'   => $routines,
            'junctions'  => $junctions,
            'nrInactive' => $nrInactive,
            'brukerinfo' => $brukerinfo
		]);
    }

    public function startWorkout (Routine $routine)
    {
    	$exercises = RoutineJunction::where('routine_id', $routine->id)
    		->get();

        $brukerinfo = Auth::user();

        $topNav = [
            0 => [
                'url'  => '/dashboard/start',
                'name' => 'Start Workout'
            ],
            1 => [
                'url'  => '/dashboard/start/' . $routine->id,
                'name' => $routine->routine_name
            ]
        ];

        // If gymming is in progres, do not reset sessions
        if (session('gymming') != $routine->id) {
            session()->forget('exercises');
            session()->forget('gymming');
            session()->forget('started_gymming');
            // Proper SQL date
            $dateTime = Carbon::now();

            foreach ($exercises as $exercise) {
                session()->put([
                      $exercise->exercise_name => $exercise->id
                ]);
            }
            session(['gymming' => $routine->id]);
            session(['started_gymming' => $dateTime]);
        }

        return view('workouts.startWorkout', [
            'topNav'     => $topNav,
            'exercises'  => $exercises,
            'routine_id' => $routine->id,
            'brukerinfo' => $brukerinfo
        ]);
    }

    public function viewWorkouts (Workout $workout)
    {
        $brukerinfo = Auth::user();

        $workouts = Workout::where('workouts.user_id', Auth::id())
            ->join('routines', 'workouts.routine_id', '=', 'routines.id')
            ->select('workouts.id AS workout_id', 'workouts.routine_id', 'workouts.created_at', 'workouts.updated_at', 'routines.routine_name')
            ->orderBy('workouts.created_at', 'DESC')
            ->get();
            
        $topNav = [
            0 => [
                'url'  => '/dashboard/workouts',
                'name' => 'My Workouts'
            ]
        ];

        return view('workouts.myWorkouts', [
            'topNav'     => $topNav,
            'workouts'   => $workouts,
            'brukerinfo' => $brukerinfo
        ]);
    }

    public function deleteWorkout (Workout $workout)
    {
        
    }

    public function finishWorkout ($routine_id)
    {
        $session = session('exercises');

        if ($session != null || $session) {

        
            $session_started = session('started_gymming');
            $currTime = Carbon::now();
            $duration = $currTime->diffInMinutes($session_started);

            session()->forget('exercises');
            session()->forget('gymming');
            session()->forget('started_gymming');

            $user_id = Auth::id();

            $workout = new Workout;
            $workout->routine_id = $routine_id;
            $workout->user_id = $user_id;
            $workout->duration_minutes = $duration;
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
                $note->routine_junction_id  = $session_exercise['routine_junction_id'];
                $note->note                 = $session_exercise['note']['text'];
                $note->label                = $session_exercise['note']['labelType'];
                $note->save();
            }
            
            return redirect('/dashboard/workouts')->with('success', 'Workout saved. Good job!');
        }

        return redirect('/dashboard/workouts')->with('danger', 'Something went wrong! Please try again.');
    }

}
