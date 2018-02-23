<?php

namespace Logit\Http\Controllers;

use Logit\Settings;
use Logit\WorkoutJunction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timezone');
    }
    
    public function myProfile ()
    {
		$user = Auth::user();
        $settings = Settings::where('user_id', Auth::id())
            ->first();

        $exercises = WorkoutJunction::select('exercise_name')
            ->where('user_id', $user->id)
            ->orderBy('exercise_name')
            ->distinct()
            ->get();

		$topNav = [
            0 => [
                'url'  => '/user',
                'name' => 'My Profile'
            ]
        ];

		return view('user.myProfile', [
			'topNav'    => $topNav,
            'settings'  => $settings,
            'exercises' => $exercises,
			'user'      => $user
		]);
    }

    public function editProfile (Request $request)
    {
    	$data = $request->all();
    	$user = Auth::user();

    	$user->update($data);
    	return back()->with('script_success', 'Profile updated.');
    }
}
