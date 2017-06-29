<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Logit\Workout;
use Logit\Routine;
use Logit\RoutineJunction;

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
        $this->middleware('timezone');
    }

    public function routines ()
    {
        $brukerinfo = Auth::user();
        $routines = Routine::where('user_id', Auth::id())
            ->orderBy('routines.routine_name', 'asc')
            ->get();

        foreach ($routines as $key => $val) {
            $last_used = Workout::where([
                ['user_id', Auth::id()],
                ['routine_id', $routines[$key]['id']],
            ])
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

            $times_used = Workout::where([
                ['user_id', Auth::id()],
                ['routine_id', $routines[$key]['id']],
            ])
            ->count();

            if ($last_used) {
                $routines[$key] = collect(['last_used'  => $last_used->created_at])->merge($routines[$key]);
            } else {
                $routines[$key] = collect(['last_used'  => 'N/A'])->merge($routines[$key]);
            }

            if ($times_used) {
                $routines[$key] = collect(['times_used' => $times_used])->merge($routines[$key]);
            } else {
                $routines[$key] = collect(['times_used' => 0])->merge($routines[$key]);
            }
        }

        $topNav = [
            0 => [
                'url'  => '/dashboard/my_routines',
                'name' => 'My Routines'
            ]
        ];

        return view('routines.routines', [
            'topNav'     => $topNav,
            'brukerinfo' => $brukerinfo,
            'routines'   => $routines
        ]);
    }

    public function addRoutine ()
    {
        $topNav = [
            0 => [
                'url'  => '/dashboard/my_routines',
                'name' => 'My Routines'
            ],
            1 => [
                'url'  => '/dashboard/my_routines/add_routine',
                'name' => 'Add Routine'
            ]
        ];
        $brukerinfo = Auth::user();
        
        return view('routines.addRoutine', [
            'topNav'     => $topNav,
            'brukerinfo' => $brukerinfo,
        ]);
    }

    public function insertRoutine (Request $request)
    {   

        $routine = new Routine;
        $routine->user_id = Auth::id();
        $routine->routine_name = $request->routine_name;
        $routine->save();

        $exercises = $request->exercises;
        $supersets = $request->supersets;

        foreach ($exercises as $exercise) {
            $junction = new RoutineJunction;

            $junction->type          = 'regular';
            $junction->routine_id    = $routine->id;
            $junction->user_id 		 = Auth::id();
            $junction->exercise_name = $exercise['exercise_name'];
            $junction->muscle_group  = $exercise['muscle_group'];
            $junction->goal_weight   = $exercise['goal_weight'];
            $junction->goal_sets     = $exercise['goal_sets'];
            $junction->goal_reps     = $exercise['goal_reps'];

            $junction->save();
        }

        foreach ($supersets as $superset) {
            // Grabs the name because that's acceable here.
            $superset_name = $superset['superset_name'];
            
            // Removes first datapoint in array, as this is the superset name. We already got that in out memory.
            foreach(array_slice($superset,1) as $exercise) {
                $junction = new RoutineJunction;
                
                $junction->type          = 'superset';
                $junction->routine_id    = $routine->id;
                $junction->user_id       = Auth::id();
                $junction->superset_name = $superset_name;
                $junction->exercise_name = $exercise['exercise_name'];
                $junction->muscle_group  = $exercise['muscle_group'];
                $junction->goal_weight   = $exercise['goal_weight'];
                $junction->goal_sets     = $exercise['goal_sets'];
                $junction->goal_reps     = $exercise['goal_reps'];
                
                $junction->save();
            }

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
			
            RoutineJunction::where('routine_id', $request->routineId)
				->delete();

			// $routine = new Routine;
	        $routine->user_id = Auth::id();
	        $routine->routine_name = $request->routine_name;
	        $routine->update();

	        foreach ($request->exercises as $value) {
	            $junction = new RoutineJunction;

	            $junction->routine_id    = $routine->id;
	            $junction->user_id 		 = Auth::id();
	            $junction->exercise_name = $value['exercise_name'];
	            $junction->muscle_group  = $value['muscle_group'];
	            $junction->goal_weight   = $value['goal_weight'];
	            $junction->goal_sets     = $value['goal_sets'];
	            $junction->goal_reps     = $value['goal_reps'];

                if (!array_key_exists('is_warmup', $value)) {
                    $junction->is_warmup = 0;
                } else {
                    $junction->is_warmup = 1;
                }

	            $junction->save();
	        }
        	return back()->with('success', 'Routine updated!');
		}
		return back()->with('danger', 'Something went wrong. Please try again!');
	}

    public function changeStatus (Request $request, Routine $routine)
    {
        if ($routine->user_id == Auth::id()) {
            // Status 0 = Going from inactive to active
            if ($request->status == 0) {
                $routine->active = 1;
                $routine->save();
                return response()->json(array('success' => true, 'status' => 'Now active'));
            } 

            // Status 1 = Going from active to inactive
            else {
                $routine->active = 0;
                $routine->save();
                return response()->json(array('success' => true, 'status' => 'Now inactive'));
            }
        }
        return response()->json(array('success' => false));
    }
}
