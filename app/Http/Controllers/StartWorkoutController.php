<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Logit\Classes\LogitFunctions;
use Logit\RoutineJunction;
use Logit\WorkoutJunction;
use Logit\Settings;
use Logit\Routine;
use Logit\Workout;
use Logit\Note;
use Logit\User;

class StartWorkoutController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timezone');
    }

	/**
     * Shows all current workouts that are active
     *
     * @return \Illuminate\Http\Response
     */
    public function index ()
    {
    	$routines = Routine::where([
                ['user_id', '=', Auth::id()],
                ['active', '=', 1],
                ['pending', 0],
                ['deleted', 0],
            ])
            ->orderBy('routine_name', 'ASC')
    	 	->get();

        foreach ($routines as $key => $val) {
            $last_used = Workout::where([
                ['user_id', Auth::id()],
                ['routine_id', $routines[$key]['id']],
            ])
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

            if ($last_used) {
                $routines[$key] = collect([
                    'last_used' => Carbon::parse($last_used->created_at)->diffForHumans(null, false, false, 2)
                    ])->merge($routines[$key]);
            }
            else {
                $routines[$key] = collect(['last_used' => 'N/A'])->merge($routines[$key]);
            }
        }

        $muscleGroups = Routine::where([
                ['routines.user_id', '=', Auth::id()],
                ['routines.active', '=', 1],
            ])
            ->orderBy('routines.routine_name', 'ASC')
            ->join('routine_junctions', 'routine_junctions.routine_id', '=', 'routines.id')
            ->get();

        $sortMuscleGroups = [];
        $topMuscleGroup = [];
        $muscleReference = ['legs','chest','back','shoulders','abs','arms'];

        // Adds all IDs to topMuscleGroup
        foreach ($routines as $value) {
            $topMuscleGroup[$routines[$key]['id']] = [
                'legs' => 0,
                'chest' => 0,
                'back' => 0,
                'shoulders' => 0,
                'abs' => 0,
                'arms' => 0
            ];
        }

        // Creates the array
        foreach ($muscleGroups as $key => $value) {
            $sortMuscleGroups[$key] = [
                'muscle_group' => $value->muscle_group,
                'routine_id' => $value->routine_id
            ];
        }

        foreach ($sortMuscleGroups as $value) {
            if (isset($topMuscleGroup[$value['routine_id']][$value['muscle_group']])) {
                if ($value['muscle_group'] == 'biceps' || $value['muscle_group'] == 'triceps' || $value['muscle_group'] == 'forearms') {
                    $topMuscleGroup[$value['routine_id']]['arms']
                        = $topMuscleGroup[$value['routine_id']]['arms'] + 1;
                } else {
                    $topMuscleGroup[$value['routine_id']][$value['muscle_group']]
                        = $topMuscleGroup[$value['routine_id']][$value['muscle_group']] + 1;
                }
            } else {
                if ($value['muscle_group'] == 'biceps' || $value['muscle_group'] == 'triceps') {
                    $topMuscleGroup[$value['routine_id']]['arms'] = 1;
                } else {
                    $topMuscleGroup[$value['routine_id']][$value['muscle_group']] = 1;
                }
            }
        }

        // Sorts the array
        foreach ($topMuscleGroup as $key => $value) {
            arsort($topMuscleGroup[$key]);
        }

        $nrInactive = Routine::where([
                ['user_id', '=', Auth::id()],
                ['active', '=', 0],
            ])
            ->count();

        $user = Auth::user();

        $topNav = [
            0 => [
                'url'  => '/start-workout',
                'name' => 'Start Workout'
            ]
        ];

        return view('startWorkout.index', [
            'topNav'         => $topNav,
            'routines'       => $routines,
            'topMuscleGroup' => $topMuscleGroup,
            'nrInactive'     => $nrInactive,
            'user'           => $user
		]);
    }

    /**
     * Completes a workout. Transfers all data in session to DB
     *
     * @param  Int $routine_id
     * @return \Illuminate\Http\Response
     */
    public function create ($routine_id)
    {
        $exercises = session('exercises');
        $supersets = session('supersets');

        if ( ($exercises != null || $exercises) || ($supersets != null || $supersets) ) {

            $settings = Settings::where('user_id', Auth::id())->first();

            $session_started = session('started_gymming');
            $currTime = Carbon::now();
            $duration = $currTime->diffInMinutes($session_started);

            #session()->forget('exercises');
            #session()->forget('supersets');
            #session()->forget('gymming');
            #session()->forget('started_gymming');

            $user_id = Auth::id();

            $workout = new Workout;
            $workout->routine_id = $routine_id;
            $workout->user_id = $user_id;
            $workout->date_started = $session_started;
            $workout->duration_minutes = $duration;
            $workout->save();

            /* Regular exercies */
            if ($exercises) {
                foreach ($exercises as $session_exercise) {
                    $exercise_name = $session_exercise['exercise_name'];
                    foreach ($session_exercise['exercises'] as $exercise_specific) {
                        $exercise = new WorkoutJunction;
                        $exercise->workout_id       = $workout->id;
                        $exercise->user_id          = $user_id;
                        $exercise->routine_id       = $routine_id;
                        $exercise->exercise_name    = $exercise_name;
                        $exercise->is_warmup        = $exercise_specific['is_warmup'];
                        $exercise->weight_type      = $exercise_specific['weight_type'];
                        $exercise->reps             = $exercise_specific['reps'];
                        $exercise->set_nr           = $exercise_specific['set'];

                        // If the current exercise if of type band, set the weight to 0.
                        if (!array_key_exists("weight", $exercise_specific) && $exercise_specific['weight_type'] === 'band') {
                            $exercise->weight       = 0;
                            $exercise->band_type    = $exercise_specific['band_type'];
                        }
                        else {
                            $exercise->weight       = $exercise_specific['weight'];
                        }

                        $exercise->save();
                    }

                    if ($session_exercise['note']['text'] && strlen($session_exercise['note']['text']) > 0) {
                        $note = new Note;
                        $note->user_id              = $user_id;
                        $note->routine_junction_id  = $session_exercise['routine_junction_id'];
                        $note->exercise_name        = $session_exercise['exercise_name'];
                        $note->note                 = $session_exercise['note']['text'];
                        $note->label                = $session_exercise['note']['labelType'];
                        $note->save();
                    }
                }
            }

            /* Supersets */
            if ($supersets) {
                foreach ($supersets as $session_exercise) {
                    $superset_name  = $session_exercise['superset_name'];
                    foreach ($session_exercise['exercises'] as $exercise_specific) {
                        $exercise = new WorkoutJunction;
                        $exercise->workout_id       = $workout->id;
                        $exercise->user_id          = $user_id;
                        $exercise->routine_id       = $routine_id;
                        $exercise->exercise_name    = $exercise_specific['exercise_name'];
                        $exercise->reps             = $exercise_specific['reps'];
                        $exercise->set_nr           = $exercise_specific['set'];
                        
                        // If the current exercise if of type band, set the weight to 0.
                        if (!array_key_exists("weight", $exercise_specific) && $exercise_specific['weight_type'] === 'band') {
                            $exercise->weight       = 0;
                            $exercise->band_type    = $exercise_specific['band_type'];
                        }
                        else {
                            $exercise->weight       = $exercise_specific['weight'];
                        }

                        $exercise->save();
                    }
                    if ($session_exercise['note']['text'] && strlen($session_exercise['note']['text']) > 0) {
                        $note = new Note;
                        $note->user_id              = $user_id;
                        $note->routine_junction_id  = $session_exercise['routine_junction_id'];
                        $note->exercise_name        = $superset_name;
                        $note->note                 = $session_exercise['note']['text'];
                        $note->label                = $session_exercise['note']['labelType'];
                        $note->save();
                    }

                }
            }

            $routine = Routine::where('id', $routine_id)->first();
            $activity = 'Finished a workout! (Routine: ' . $routine->routine_name . ")";
            LogitFunctions::setActivity('routine', $activity);

            if ($settings->recap == 1) {
                return redirect('/workouts/' . $workout->id . '/recap' )->with('success', 'Workout saved. Good job! Here is your recap');
            }
            return redirect('/workouts')->with('success', 'Workout saved. Good job!');
        }

        return redirect('/workouts')->with('danger', 'Something went wrong! Please try again.');
    }

    /**
     * Gets all connected exercises to a routine
     *
     * @param  Model Routine
     * @return \Illuminate\Http\Response
     */
    public function read (Routine $routine)
    {
        $allExercises = RoutineJunction::where('routine_id', $routine->id)
            ->orderBy('order_nr', 'ASC')
            ->get();

    	$regular = RoutineJunction::where([
                ['routine_id', $routine->id],
                ['type', 'regular']
            ])
            ->orderBy('order_nr', 'ASC')
    		->get();

        $supersets = RoutineJunction::where([
                ['routine_id', $routine->id],
                ['type', 'superset']
            ])
            ->orderBy('order_nr', 'ASC')
            ->get();

        $user = Auth::user();

        $settings = Settings::where('user_id', $user->id)->first();
        $timerSettings = array(
            'direction' => $settings->timer_direction,
            'play_sound' => $settings->timer_play_sound,
            'seconds' => $settings->timer_seconds,
            'minutes' => $settings->timer_minutes,
        );

        $topNav = [
            0 => [
                'url'  => '/start-workout',
                'name' => 'Start Workout'
            ],
            1 => [
                'url'  => '/start-workout/' . $routine->id,
                'name' => $routine->routine_name
            ]
        ];

        // If gymming is in progres, do not reset sessions
        // Otherwise load everything up!
        if (session('gymming') != $routine->id) {
            session()->forget('exercises');
            session()->forget('gymming');
            session()->forget('started_gymming');
            session()->forget('supersets');
            // Proper SQL date
            $dateTime = Carbon::now();

            foreach ($regular as $exercise) {
                session()->put([
                      $exercise->exercise_name => $exercise->id
                ]);
            }

            foreach ($supersets as $superset) {
                session()->put([
                      $superset->superset_name => $exercise->id
                ]);
            }
            session(['gymming' => $routine->id]);
            session(['started_gymming' => $dateTime]);

            $activity = 'Started the routine ' . $routine->routine_name;
            LogitFunctions::setActivity('routine', $activity);
        }
        
        return view('startWorkout.start', [
            'topNav'        => $topNav,
            'exercises'     => $allExercises,
            'routine_id'    => $routine->id,
            'user'          => $user,
            'settings'      => $settings,
            'timerSettings' => $timerSettings,
        ]);
    }

    /**
     * Flushes sessions connected to gymming and exercises
     *
     * @return Redirect with Response
     */
    public function clearSession ()
    {
        session()->forget('exercises');
        session()->forget('gymming');
        return redirect('/start-workout')->with('script_success', 'Workout successfully stopped');
    }
}
