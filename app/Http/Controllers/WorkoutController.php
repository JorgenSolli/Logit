<?php

namespace Logit\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Logit\Classes\LogitFunctions;
use Logit\RoutineJunction;
use Logit\WorkoutJunction;
use Logit\Settings;
use Logit\Routine;
use Logit\Workout;
use Logit\Note;
use Logit\User;

class WorkoutController extends Controller
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
    public function selectWorkout ()
    {
    	$routines = Routine::where([
                ['user_id', '=', Auth::id()],
                ['active', '=', 1],
                ['pending', 0],
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
                    'last_used' => Carbon::parse($last_used->created_at)->diffForHumans()
                    ])->merge($routines[$key]);

                $routines[$key] = collect([
                    'last_used_sortdate' => Carbon::parse($last_used->created_at)->diffForHumans()
                    ])->merge($routines[$key]);
            }
            else {
                $routines[$key] = collect(['last_used' => 'N/A'])->merge($routines[$key]);
                $routines[$key] = collect(['last_used_sortdate' => '0'])->merge($routines[$key]);
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
                if ($value['muscle_group'] == 'biceps' || $value['muscle_group'] == 'triceps') {
                    dd("hit");
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

        $brukerinfo = Auth::user();

        $topNav = [
            0 => [
                'url'  => '/dashboard/start',
                'name' => 'Start Workout'
            ]
        ];

        return view('workouts.selectWorkout', [
            'topNav'         => $topNav,
            'routines'       => $routines,
            'topMuscleGroup' => $topMuscleGroup,
            'nrInactive'     => $nrInactive,
            'brukerinfo'     => $brukerinfo
		]);
    }

    /**
     * Shows a spesific workout
     *
     * @param  Int $workoutId
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Deleted a spesific workout
     *
     * @param  Model Workout
     * @return \Illuminate\Http\Response
     */
    public function deleteWorkout (Workout $workout)
    {
        if ($workout->user_id == Auth::id()) {
            WorkoutJunction::where('workout_id', $workout->id)
                ->delete();
            $workout->delete();

            return response()->json(array('success' => true));
        }
    }

    /**
     * Updates a spesific workout
     *
     * @param  Request
     * @param  Int $workoutId
     * @return \Illuminate\Http\Response
     */
    public function updateWorkout (Request $request, Workout $workout)
    {
        if ($workout->user_id == Auth::id()) {

            WorkoutJunction::where([
                    ['user_id', '=', Auth::id()],
                    ['id', '=', $request->junction_id]
                ])
                ->update([
                    'weight' => $request->weight,
                    'weight_type' => $request->weight_type,
                    'band_type' => $request->band_type,
                    'reps' => $request->reps
                ]);

            return response()->json(array('success' => true));
        }
    }

    /**
     * Gets all connected exercises to a routine
     *
     * @param  Model Routine
     * @return \Illuminate\Http\Response
     */
    public function startWorkout (Routine $routine)
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

        $brukerinfo = Auth::user();

        $settings = Settings::where('user_id', $brukerinfo->id)->first();

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
        }

        return view('workouts.startWorkout', [
            'topNav'     => $topNav,
            'exercises'  => $allExercises,
            'routine_id' => $routine->id,
            'brukerinfo' => $brukerinfo,
            'settings'   => $settings,
        ]);
    }

    /**
     * View all previously completed exercises
     *
     * @return \Illuminate\Http\Response
     */
    public function viewWorkouts ()
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

    /**
     * Completes a workout. Transfers all data in session to DB
     *
     * @param  Int $routine_id
     * @return \Illuminate\Http\Response
     */
    public function finishWorkout ($routine_id)
    {
        $exercises = session('exercises');
        $supersets = session('supersets');

        if ( ($exercises != null || $exercises) || ($supersets != null || $supersets) ) {

            $settings = Settings::where('user_id', Auth::id())->first();

            $session_started = session('started_gymming');
            $currTime = Carbon::now();
            $duration = $currTime->diffInMinutes($session_started);

            session()->forget('exercises');
            session()->forget('supersets');
            session()->forget('gymming');
            session()->forget('started_gymming');

            $user_id = Auth::id();

            $workout = new Workout;
            $workout->routine_id = $routine_id;
            $workout->user_id = $user_id;
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
                        $exercise->is_warmup		= $exercise_specific['is_warmup'];
                        $exercise->weight_type      = $exercise_specific['weight_type'];
                        $exercise->reps             = $exercise_specific['reps'];
                        $exercise->set_nr           = $exercise_specific['set'];

                        // If the current exercise if of type band, se the weight to 0.
                        if ($exercise_specific['weight'] === null && $exercise_specific['weight_type'] === 'band') {
                            $exercise->weight       = 0;
                        }
                        else {
                            $exercise->weight       = $exercise_specific['weight'];
                        }
                        $exercise->band_type        = $exercise_specific['band_type'];

                        $exercise->save();
                    }

                    $note = new Note;
                    $note->user_id              = $user_id;
                    $note->routine_junction_id  = $session_exercise['routine_junction_id'];
                    $note->note                 = $session_exercise['note']['text'];
                    $note->label                = $session_exercise['note']['labelType'];
                    $note->save();
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
            }


            if ($settings->recap == 1) {
                return redirect('/dashboard/workout/recap/' . $workout->id)->with('success', 'Workout saved. Good job! Here is your recap');
            }
            return redirect('/dashboard/workouts')->with('success', 'Workout saved. Good job!');
        }

        return redirect('/dashboard/workouts')->with('danger', 'Something went wrong! Please try again.');
    }

    /**
     * Shows data from a specific workout
     *
     * @param  Model Workout
     * @return \Illuminate\Http\Response
     */
    public function recap (Workout $workout)
    {
        $brukerinfo = Auth::user();
        $workoutName = Routine::where('id', $workout->routine_id)
            ->first();

        $minutes = $workout->duration_minutes;

        $totalSets = WorkoutJunction::where('workout_id', $workout->id)
            ->get()
            ->count();

        $totalExercises = WorkoutJunction::where([
                ['workout_id', $workout->id],
                ['set_nr', 1],
                ['is_warmup', 0]
            ])
            ->get()
            ->count();

        $avgRestTime = LogitFunctions::parseRestTime($minutes / $totalSets);
        $time = LogitFunctions::parseMinutes($minutes);

        $topNav = [
            0 => [
                'url'  => '/dashboard/workouts',
                'name' => 'My Workouts'
            ],
            1 => [
                'url'  => '/dashboard/workouts/',
                'name' => $workoutName->routine_name
            ],
            2 => [
                'url'  => '/dashboard/workouts/recap/' . $workout->id,
                'name' => 'Recap'
            ]
        ];

        return view('workouts.recap', [
            'brukerinfo'     => $brukerinfo,
            'topNav'         => $topNav,
            'workout'        => $workout,
            'time'           => $time,
            'avgRestTime'    => $avgRestTime,
            'totalExercises' => $totalExercises
        ]);
    }
}