<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Logit\Workout;
use Logit\Routine;
use Logit\RoutineJunction;
use Carbon;

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
        $routines = Routine::where([
                ['user_id', Auth::id()],
                ['pending', 0],
            ])
            ->orderBy('routines.routine_name', 'asc')
            ->get();

        $pending = Routine::where([
                ['user_id', Auth::id()],
                ['pending', 1],
            ])
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
                $routines[$key] = collect(['last_used'  => Carbon\Carbon::parse($last_used->created_at)->format('d/m/Y H:i')])->merge($routines[$key]);
                $routines[$key] = collect(['last_used_sortdate'  => Carbon\Carbon::parse($last_used->created_at)->format('Y/m/d H:i')])->merge($routines[$key]);
            } else {
                $routines[$key] = collect(['last_used'  => 'N/A'])->merge($routines[$key]);
                $routines[$key] = collect(['last_used_sortdate'  => '0'])->merge($routines[$key]);
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
            'routines'   => $routines,
            'pending'    => $pending,
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

    public function acceptRoutine ($routineId)
    {
        $routine = Routine::where('id', $routineId)->firstOrFail();

        if (!$routine) {
            abort(403, "You are not the owner of this routine");
        }

        $routine->pending = 0;
        $routine->save();

        return back()->with('success', 'Routine added to your collection.');
    }

    public function insertRoutine (Request $request)
    {   
        $routine = new Routine;
        $routine->user_id = Auth::id();
        $routine->routine_name = $request->routine_name;
        $routine->save();

        $exercises = $request->exercises;
        $supersets = $request->supersets;

        if ($exercises) {
            foreach ($exercises as $exercise) {
                $junction = new RoutineJunction;

                $junction->type          = 'regular';
                $junction->routine_id    = $routine->id;
                $junction->user_id       = Auth::id();
                $junction->order_nr      = $exercise['order_nr'];
                $junction->exercise_name = $exercise['exercise_name'];
                $junction->muscle_group  = $exercise['muscle_group'];
                $junction->goal_weight   = $exercise['goal_weight'];
                $junction->goal_sets     = $exercise['goal_sets'];
                $junction->goal_reps     = $exercise['goal_reps'];
                $junction->media         = $exercise['media'];

                if (!array_key_exists('is_warmup', $exercise)) {
                    $junction->is_warmup = 0;
                } else {
                    $junction->is_warmup = 1;
                }

                $junction->save();
            }
        }

        if ($supersets) {
            foreach ($supersets as $superset) {
                // Grabs the name because that's acceable here.
                $superset_name = $superset['superset_name'];
                $order_nr      = $superset['order_nr'];
                
                // Removes first two datapoints in array, as this is the superset name and order. We already have this in out memory.
                foreach(array_slice($superset,2) as $exercise) {
                    $junction = new RoutineJunction;
                    $junction->type          = 'superset';
                    $junction->routine_id    = $routine->id;
                    $junction->user_id       = Auth::id();
                    $junction->superset_name = $superset_name;
                    $junction->order_nr      = $order_nr;
                    $junction->exercise_name = $exercise['exercise_name'];
                    $junction->muscle_group  = $exercise['muscle_group'];
                    $junction->goal_weight   = $exercise['goal_weight'];
                    $junction->goal_sets     = $exercise['goal_sets'];
                    $junction->goal_reps     = $exercise['goal_reps'];
                    $junction->media         = $exercise['media'];

                    if (!array_key_exists('is_warmup', $exercise)) {
                        $junction->is_warmup = 0;
                    } else {
                        $junction->is_warmup = 1;
                    }
                    
                    $junction->save();
                }

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
			$junctions = RoutineJunction::where('routine_id', $routine->id)
                ->orderBy('order_nr', 'ASC')
                ->get();

            $supersets = RoutineJunction::where([
                ['routine_id', $routine->id],
                ['type', 'superset']
            ])->get();

			$returnHTML = view('routines.viewRoutine')
				->with('routine', $routine)
				->with('junctions', $junctions)
                ->with('supersets', $supersets)
				->render();
			return response()->json(array('success' => true, 'data'=>$returnHTML));
		}
	}

	public function updateRoutine (Request $request, Routine $routine)
	{
		if ($routine->user_id == Auth::id()) {
            // Deletes old junctions and inserts new ones
            RoutineJunction::where('routine_id', $request->routineId)->delete();

            $routine->user_id = Auth::id();
            $routine->routine_name = $request->routine_name;
            $routine->update();

            $exercises = $request->exercises;
            $supersets = $request->supersets;
            if ($exercises) {
                foreach ($exercises as $exercise) {
                    $junction = new RoutineJunction;

                    $junction->type          = 'regular';
                    $junction->routine_id    = $routine->id;
                    $junction->user_id       = Auth::id();
                    $junction->order_nr      = $exercise['order_nr'];
                    $junction->exercise_name = $exercise['exercise_name'];
                    $junction->muscle_group  = $exercise['muscle_group'];
                    $junction->goal_weight   = $exercise['goal_weight'];
                    $junction->goal_sets     = $exercise['goal_sets'];
                    $junction->goal_reps     = $exercise['goal_reps'];

                    if (array_key_exists('is_warmup', $exercise)) {
                        $junction->is_warmup = 1;
                    } else {
                        $junction->is_warmup = 0;
                    }

                    if (array_key_exists('media', $exercise)) {
                        $junction->media = $exercise['media'];
                    } else {
                        $junction->media = null;
                    }

                    $junction->save();
                }
            }

            if ($supersets) {
                foreach ($supersets as $superset) {
                    // Grabs the name because that's acceable here.
                    $superset_name = $superset['superset_name'];
                    $order_nr      = $superset['order_nr'];
                    
                    // Removes first two datapoints in array, as this is the superset name and order. We already have this in out memory.
                    foreach(array_slice($superset,2) as $exercise) {
                        $junction = new RoutineJunction;
                        $junction->type          = 'superset';
                        $junction->routine_id    = $routine->id;
                        $junction->user_id       = Auth::id();
                        $junction->superset_name = $superset_name;
                        $junction->order_nr      = $order_nr;
                        $junction->exercise_name = $exercise['exercise_name'];
                        $junction->muscle_group  = $exercise['muscle_group'];
                        $junction->goal_weight   = $exercise['goal_weight'];
                        $junction->goal_sets     = $exercise['goal_sets'];
                        $junction->goal_reps     = $exercise['goal_reps'];

                        if (array_key_exists('is_warmup', $exercise)) {
                            $junction->is_warmup = 1;
                        } else {
                            $junction->is_warmup = 0;
                        }

                        if (array_key_exists('media', $exercise)) {
                            $junction->media = $exercise['media'];
                        } else {
                            $junction->media = null;
                        }
                        
                        $junction->save();
                    }
                }
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

    public function previewRoutine (Request $request)
    {
        $routine = $request->routine;
        $routines = RoutineJunction::where([
                ['routine_id', $routine],
                ['user_id', Auth::id()]
            ])
            ->get();

        $returnHTML = view('routines.previewRoutine')
            ->with('routines', $routines)
            ->render();

        return response()->json(array('success' => true, 'data'=>$returnHTML));
    }
}
