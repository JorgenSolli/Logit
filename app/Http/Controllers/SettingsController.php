<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Logit\Settings;
use Logit\RoutineJunction;
use Logit\WorkoutJunction;

class SettingsController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timezone');
    }
    
    public function settings ()
    {
		$brukerinfo = Auth::user();

        $settings = Settings::where('user_id', Auth::id())
            ->first();
            
        $exercises = WorkoutJunction::select('exercise_name')
            ->where('user_id', $brukerinfo->id)
            ->orderBy('exercise_name')
            ->distinct()
            ->get();

        $topNav = [
            0 => [
                'url'  => '/user/settings',
                'name' => 'Settings'
            ]
        ];

        return view('user.settings', [
            'topNav'     => $topNav,
            'settings'   => $settings,
            'exercises'  => $exercises,
            'brukerinfo' => $brukerinfo
        ]);
    }

    public function editSettings (Request $request)
    {
        $data = $request->all();

        $settings = Settings::where('user_id', Auth::id())
            ->first();

        // If there is not an instance of the user already in our settingstable
        if (!$settings) {
            $settings = new Settings;
        }

        $settings->user_id = Auth::id();
        $settings->unit = $data['unit'];
        $settings->timezone = $data['timezone'];

        if (!array_key_exists('recap', $data)) {
            $settings->recap = 0;
        } else {
            $settings->recap = 1;
        }

        if (!array_key_exists('share_workouts', $data)) {
            $settings->share_workouts = 0;
        } else {
            $settings->share_workouts = 1;
        }

        if (!array_key_exists('accept_friends', $data)) {
            $settings->accept_friends = 0;
        } else {
            $settings->accept_friends = 1;
        }

        if (!array_key_exists('strict_previous_exercise', $data)) {
            $settings->strict_previous_exercise = 0;
        } else {
            $settings->strict_previous_exercise = 1;
        }

        if (!array_key_exists('count_warmup_in_stats', $data)) {
            $settings->count_warmup_in_stats = 0;
        } else {
            $settings->count_warmup_in_stats = 1;
        }

        if ($settings->save()) {
            return back()->with('script_success', 'Settings updated.');
        }

        return back()->with('script_danger', 'Something went wrong. Please try again.');
    }

    public function timerSettings (Request $request)
    {   
        $data = $request->all();

        $settings = Settings::where('user_id', Auth::id())
            ->first();

        // If there is not an instance of the user already in our settingstable
        if (!$settings) {
            $settings = new Settings;
        }

        if (!array_key_exists('use_timer', $data)) {
            $settings->use_timer = 0;
        } else {
            $settings->use_timer = 1;
        }

        if (!array_key_exists('timer_play_sound', $data)) {
            $settings->timer_play_sound = 0;
        } else {
            $settings->timer_play_sound = 1;
        }

        $settings->timer_direction = $data['timer_direction'];

        if ($request->timer_minutes > 60) {
            return back()->with('script_danger', 'Seconds cannot be more then 60');
        }
        
        if ($request->timer_seconds > 60) {
            return back()->with('script_danger', 'Minutes cannot be more then 60');
        }

        $settings->timer_minutes = $request->timer_minutes;
        $settings->timer_seconds = $request->timer_seconds;

        if ($settings->save()) {
            return back()->with('script_success', 'Timer settings updated.');
        }
    }

    public function renameExercise (Request $request)
    {

        // Wraps both update in a condition check in case one fails.
        $workoutJunction = WorkoutJunction::where([
                ['user_id', Auth::id()],
                ['exercise_name', $request->old_name]
            ])->update(['exercise_name' => $request->new_name]);
            $routineJunction = RoutineJunction::where([
                ['user_id', Auth::id()],
                ['exercise_name', $request->old_name],
            ])
            ->update(['exercise_name' => $request->new_name]);

        return back()->with('script_success', 'Exercise has been renamed.');
    }

    public function getSettings ()
    {
        return Settings::where('user_id', Auth::id())->first();
    }
}
