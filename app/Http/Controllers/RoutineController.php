<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Routine;
use App\RoutineJunction;

class RoutineController extends Controller
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function routines ()
    {
        $brukerinfo = Auth::user();
        $routines = Routine::where('user_id', Auth::id())
            ->orderBy('routines.routine_name', 'asc')
            ->get();

        return view('routines.routines', [
            'brukerinfo'      => $brukerinfo,
            'routines'        => $routines
        ]);
    }

    public function addRoutine ()
    {
        return view('routines.addRoutine');
    }

    public function insertRoutine (Request $request)
    {   

        $routine = new Routine;
        $routine->user_id = Auth::id();
        $routine->routine_name = $request->routine_name;
        $routine->save();

        foreach ($request->exercises as $value) {
            $junction = new RoutineJunction;

            $junction->routine_id    = $routine->id;
            $junction->user_id 		 = Auth::id();
            $junction->exercise_name = $value['exercise_name'];
            $junction->muscle_group  = $value['muscle_group'];
            $junction->goal_weight   = $value['goal_weight'];
            $junction->goal_sets     = $value['goal_sets'];
            $junction->goal_reps     = $value['goal_reps'];

            $junction->save();
        }

        return redirect('/dashboard/my_routines');
    }

    public function deleteRoutine (Routine $routine)
    {
    	if ($routine->user_id == Auth::id()) {
	    	RoutineJunction::where('routine_id', $routine->id)
	    		->delete();
			$routine->delete();

			return back()->with('success', 'Routine deleted.');
		}
    }

    public function viewRoutine (Routine $routine)
    {
    	if ($routine->user_id == Auth::id()) {
			$junctions = RoutineJunction::where('routine_id', $routine->id)->get();

			$returnHTML = view('routines.viewRoutine')
				->with('routine', $routine)
				->with('junctions', $junctions)
				->render();
			return response()->json(array('success' => true, 'data'=>$returnHTML));
		}
	}

	public function updateRoutine (Request $request, Routine $routine)
	{
		if ($routine->user_id == Auth::id()) {
			// Deletes old recolds and inserts new ones
			$routine->delete();
			RoutineJunction::where('routine_id', $request->id)
				->delete();

			$routine = new Routine;
	        $routine->user_id = Auth::id();
	        $routine->routine_name = $request->routine_name;
	        $routine->save();

	        foreach ($request->exercises as $value) {
	            $junction = new RoutineJunction;

	            $junction->routine_id    = $routine->id;
	            $junction->user_id 		 = Auth::id();
	            $junction->exercise_name = $value['exercise_name'];
	            $junction->muscle_group  = $value['muscle_group'];
	            $junction->goal_weight   = $value['goal_weight'];
	            $junction->goal_sets     = $value['goal_sets'];
	            $junction->goal_reps     = $value['goal_reps'];

	            $junction->save();
	        }
        	return back()->with('success', 'Routine updated!');
		}
		return back()->with('danger', 'Something went wrong. Please try again!');
	}
}
