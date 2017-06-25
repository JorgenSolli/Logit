<?php

namespace Logit\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Logit\Settings;
use Logit\User;

class DashboardController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $brukerinfo = Auth::user();
        $topNav = [
            0 => [
                'url'  => '/',
                'name' => 'Dashboard'
            ]
        ];

        $firstTime = false;

        /* Checks if this is the users first time visiting */
        if ($brukerinfo->first_time === 1) {
            /* Setting some standard settings */
            Settings::create([
                'user_id'        => Auth::id(),
                'timezone'       => 'UTC',
                'unit'           => 'Metric',
                'recap'          => 1,
                'share_workouts' => 0,
                'accept_friends' => 0,
            ]);

            /* Letting the user know about some stuff */
            $firstTime = true;

            $brukerinfo->update(['first_time'=> 0]);
        }
        
        return view('dashboard', [
            'topNav'     => $topNav,
            'brukerinfo' => $brukerinfo,
            'firstTime'  => $firstTime,
        ]);
    }

    
}
