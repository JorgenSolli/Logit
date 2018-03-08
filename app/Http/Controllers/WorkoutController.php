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
     * View all previously completed exercises
     *
     * @return \Illuminate\Http\Response
     */
    public function index ()
    {
        $user = Auth::user();
        $workouts = Workout::where('workouts.user_id', Auth::id())
            ->join('routines', 'workouts.routine_id', '=', 'routines.id')
            ->select('workouts.id AS workout_id', 'workouts.routine_id', 'workouts.created_at', 'workouts.updated_at', 'routines.routine_name')
            ->orderBy('workouts.created_at', 'DESC')
            ->get();

        $topNav = [
            0 => [
                'url'  => '/workouts',
                'name' => 'My Workouts'
            ]
        ];

        return view('workouts.index', [
            'topNav'     => $topNav,
            'workouts'   => $workouts,
            'user'       => $user
        ]);
    }

    /**
     * Shows a spesific workout
     *
     * @param  Int $workoutId
     * @return \Illuminate\Http\Response
     */
    public function read ($workoutId)
    {
        $workoutJunction = WorkoutJunction::where('workout_id', $workoutId)
            ->where('user_id', Auth::id())
            ->get();

        $workout = workout::where('id', $workoutJunction->first()->workout_id)->first();
        $topNav = [
            0 => [
                'url'  => '/workouts',
                'name' => 'My Workouts'
            ],
            1 => [
                'url'  => '/workouts/' . $workout->id,
                'name' => Routine::where('id', $workout->routine_id)->first()->routine_name
            ]
        ];

        return view('workouts.read', [
            'topNav'          => $topNav,
            'workout'         => $workout,
            'workoutJunction' => $workoutJunction,
            'workoutId'       => $workoutId,
            'user'            => Auth::user()
        ]);
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
    public function update (Request $request, Workout $workout)
    {
        if ($workout->user_id == Auth::id()) {

            if ($request->setTime) {
                $date_started = Carbon::parse($request->date_started);
                $created_at = Carbon::parse($request->created_at);
                $duration_minutes = $date_started->diffInMinutes($created_at);

                $workout->date_started = $date_started;
                $workout->created_at = $created_at;
                $workout->duration_minutes = $duration_minutes;

                if ($date_started > $created_at) {
                    return response()->json(array('success' => false));
                }

                $workout->save();

            } else {
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
            }

            return response()->json(array('success' => true));
        }
    }

    /**
     * Shows data from a specific workout
     *
     * @param  Model Workout
     * @return \Illuminate\Http\Response
     */
    public function recap (Workout $workout)
    {
        $previousWorkout = Workout::where([
                ['routine_id', $workout->routine_id],
                ['user_id', Auth::id()],
                ['created_at', '<', $workout->created_at]
            ])
            ->orderBy('created_at', 'DESC')
            ->first();
        
        $user = Auth::user();
        $settings = Settings::where('user_id', $user->id)->first();
        
        $units = "pounds";
        if ($settings->unit === "Metric") {
            $units = "kg";
        }

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

        $totalLifted = 0;
        foreach (WorkoutJunction::where('workout_id', $workout->id)->get() as $e) {
            if ($e->weight_type === "raw") {
                $totalLifted += $e->weight * $e->reps;
            }
        }

        $time = LogitFunctions::parseMinutes($minutes);

        $avgRestTime = LogitFunctions::parseRestTime($minutes / $totalSets);

        $previousTotalSets = $lastTotalExercises = $lastAvgRestTime = $avgRestTimeLess = $lastTime = $timeLess = $totalExercisesLess = $hasPrevious = null;

        /* PREVIOUS DATA */
        $lastTotalLifted = null;
        if ($previousWorkout) {
            $hasPrevious = true;
            $previousTotalSets = WorkoutJunction::where('workout_id', $previousWorkout->id)
                ->get()
                ->count();
            
            $lastTotalExercises = WorkoutJunction::where([
                    ['workout_id', $previousWorkout->id],
                    ['set_nr', 1],
                    ['is_warmup', 0]
                ])
                ->get()
                ->count();
            
            $lastTotalLifted = 0;
            foreach (WorkoutJunction::where('workout_id', $previousWorkout->id)->get() as $e) {
                if ($e->weight_type === "raw") {
                    $lastTotalLifted += $e->weight * $e->reps;
                }
            }

            // Session rest-time previous session
            $lastAvgRestTime = LogitFunctions::parseRestTime($previousWorkout->duration_minutes / $previousTotalSets);
            
            // Up or down from last session
            $avgRestTimeLess = ($avgRestTime < $lastAvgRestTime) ? true : false;
            
            // Session time previous session
            $lastTime = LogitFunctions::parseMinutes($previousWorkout->duration_minutes);
            
            // Up or down from last session
            $timeLess = ($minutes < $previousWorkout->duration_minutes) ? true : false;

            // Up or down from last session
            $totalExercisesLess = ($totalExercises < $lastTotalExercises) ? true : false;
        }
        
        $topNav = [
            0 => [
                'url'  => '/workouts',
                'name' => 'My Workouts'
            ],
            1 => [
                'url'  => '/workouts',
                'name' => $workoutName->routine_name
            ],
            2 => [
                'url'  => '/workouts/recap/' . $workout->id,
                'name' => 'Recap'
            ]
        ];

        return view('workouts.recap', [
            'user'               => $user,
            'topNav'             => $topNav,
            'workout'            => $workout,
            'previousWorkout'    => $previousWorkout,
            'time'               => $time,
            'lastTime'           => $lastTime,
            'timeLess'           => $timeLess,
            'avgRestTime'        => $avgRestTime,
            'lastAvgRestTime'    => $lastAvgRestTime,
            'avgRestTimeLess'    => $avgRestTimeLess,
            'totalExercises'     => $totalExercises,
            'lastTotalExercises' => $lastTotalExercises,
            'totalExercisesLess' => $totalExercisesLess,
            'totalLifted'        => $totalLifted,
            'lastTotalLifted'    => $lastTotalLifted,
            'hasPrevious'        => $hasPrevious,
            'units'              => $units,
        ]);
    }
}