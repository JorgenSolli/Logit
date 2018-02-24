<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Logit\Note;
use Logit\Settings;
use Logit\RoutineJunction;
use Logit\WorkoutJunction;
use Logit\Classes\LogitFunctions;

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

        $note = LogitFunctions::getNote($exerciseId, false);

        $routine = RoutineJunction::where('id', $exerciseId)
            ->where('user_id', $userId)
            ->select('type', 'routine_id')
            ->first();

    	$exercise = RoutineJunction::where([
                ['id', $exerciseId],
                ['user_id', Auth::id()]
            ])
    		->firstOrFail();

        if ($routine->type === 'superset') {
            $ssName = $exercise->superset_name;
            $ri = $exercise->routine_id;

            $exercise = RoutineJunction::where([
                ['user_id', Auth::id()],
                ['routine_id', $ri],
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

        if ($settings->strict_previous_exercise == 1) {
            $where = [
                ['routine_id', $exercise[0]->routine_id],
                ['user_id', Auth::id()]
            ];
        } else {
            $where = [
                ['user_id', Auth::id()]
            ];
        }

        if ($routine->type === 'superset') {
            $exercises = [];
            foreach ($exercise as $e) { 
                $previousExercise = WorkoutJunction::where([
                        ['exercise_name', $e->exercise_name],
                        ['user_id', Auth::id()],
                    ])
                    ->where($where)
                    ->limit($nrOfSets)
                    ->orderBy('id', 'DESC')
                    ->get();

                array_push($exercises, array_reverse($previousExercise->toArray()));
            }
            $previousExercise = $exercises;
        }
        else {
            $previousExercise = WorkoutJunction::where('exercise_name', $exercise->exercise_name)
                ->where($where)
                ->limit($nrOfSets)
                ->orderBy('created_at', 'DESC')
                ->orderBy('set_nr', 'ASC')
                ->get();
        }

		$returnHTML = view('workouts.exercise')
            ->with('type', $routine->type)
			->with('exercise', $exercise)
            ->with('supersetsCount', $supersetsCount)
            ->with('prevExercise', $previousExercise)
			->with('nrOfSets', $nrOfSets)
            ->with('routineId', $routine->routine_id)
            ->with('exercise_id', $exerciseId)
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
    public function addExercise ($routine_id, $exerciseId, Request $request)
    {
        #return response()->json($routine_id);

        LogitFunctions::getNote($exerciseId, true);

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
