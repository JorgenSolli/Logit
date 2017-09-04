<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Logit\Note;
use Logit\Settings;
use Logit\RoutineJunction;
use Logit\WorkoutJunction;

class ExerciseController extends Controller
{
	/**
     * Gets a specific exercise belonging to the user
     *
     * @param  Int $exerciseId specifies the exercies to be grabbed
     * @return \Illuminate\Http\Response
     */
    public function getExercise ($exerciseId)
    {
        $userId = Auth::id();
        $settings = Settings::where('user_id', Auth::id())->first();

        $type = RoutineJunction::where('id', $exerciseId)
            ->where('user_id', $userId)
            ->select('type')
            ->first();
        $type = $type->type;

        $note = Note::where('routine_junction_id', $exerciseId)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();
            
        $routineId = RoutineJunction::where('id', $exerciseId)
            ->where('user_id', $userId)
            ->select('routine_id')
            ->get();

    	$exercise = RoutineJunction::where([
                ['id', $exerciseId],
                ['user_id', Auth::id()]
            ])
    		->firstOrFail();

        if ($type === 'superset') {
            $ssName = $exercise->superset_name;
            $exercise = RoutineJunction::where([
                ['user_id', Auth::id()],
                ['type', 'superset'],
                ['superset_name', $ssName]
            ])->get();
            $supersetsCount = $exercise->count();
            $nrOfSets = $exercise[0]->goal_sets;
        } 
        else {
            $supersetsCount = 0;
            $nrOfSets = $exercise->goal_sets;
        }


        if ($type === 'superset') {
            $previousExercise = null;
        }
        else {
            if ($settings->strict_previous_exercise == 1) {

                $previousExercise = WorkoutJunction::where('routine_id', $exercise->routine_id)
                    ->where('exercise_name', $exercise->exercise_name)
                    ->where('user_id', Auth::id())
                    ->limit($nrOfSets)
                    ->orderBy('created_at', 'DESC')
                    ->orderBy('set_nr', 'ASC')
                    ->get();
            } else {
                $previousExercise = WorkoutJunction::where('exercise_name', $exercise->exercise_name)
                    ->where('user_id', Auth::id())
                    ->limit($nrOfSets)
                    ->orderBy('created_at', 'DESC')
                    ->orderBy('set_nr', 'ASC')
                    ->get();
            }
        }

		$returnHTML = view('workouts.exercise')
            ->with('type', $type)
			->with('exercise', $exercise)
            ->with('supersetsCount', $supersetsCount)
            ->with('prevExercise', $previousExercise)
			->with('nrOfSets', $nrOfSets)
            ->with('routineId', $routineId)
            ->with('note', $note)
			->render();

		return response()->json(array('success' => true, 'data'=>$returnHTML));
    }
    
    /**
     * Adds an exercise to the session
     *
     * @param  Int $routine_id specifies current routine
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function addExercise ($routine_id, Request $request)
    {   
        if ($request->type === 'regular') {
            $junction = $request->routine_junction_id;

            session()->forget($request->exercise_name);

            session()->push('exercises', [
                'exercise_name' => $request->exercise_name, 
                'note' => ([
                    'text' => $request->note,
                    'labelType' => $request->labelType
                ]),
                'routine_junction_id' => $junction,
                'exercises' => (
                    $request->exercise
                ),
            ]);
        }
        /* Superset */
        else {
            session()->forget($request->superset_name);

            $junction = $request->routine_junction_id;
            $note = $request->note;
            $labelType = $request->labelType;
            $name = $request->superset_name;

            foreach ($request->superset as $set) {
                session()->push('supersets', [
                    'superset_name' => $name, 
                    'note' => ([
                        'text' => $note,
                        'labelType' => $labelType
                    ]),
                    'routine_junction_id' => $junction,
                    'exercises' => (
                        $set
                    ),
                ]);
            }
        }

        // return back()->with('script_success', 'Exercise saved. Good job!');
        $response = [
            'success' => true,
            'message' => 'Exercise saved. Good job!',
            'id'      => $junction, 
        ];
        return $response;
    }
}
