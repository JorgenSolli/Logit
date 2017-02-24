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

        return view('routines', [
            'brukerinfo'      => $brukerinfo,
            'routines'        => $routines
        ]);
    }

    public function addRoutine ()
    {
        return view('addRoutine');
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
            $junction->exercise_name = $value['exercise_name'];
            $junction->muscle_group  = $value['muscle_group'];
            $junction->goal_weight   = $value['goal_weight'];
            $junction->goal_sets     = $value['goal_sets'];
            $junction->goal_reps     = $value['goal_reps'];

            $junction->save();
        }

        return redirect('/dashboard/my_routines');
    }
}
