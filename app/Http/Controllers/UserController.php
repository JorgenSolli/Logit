<?php

namespace App\Http\Controllers;

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
		$brukerinfo = Auth::user();
		$topNav = [
            0 => [
                'url'  => '/user',
                'name' => 'My Profile'
            ]
        ];

		return view('user.myProfile', [
			'topNav'     => $topNav,
			'brukerinfo' => $brukerinfo
		]);
    }

    public function editProfile (Request $request)
    {
    	$data = $request->all();
    	$user = Auth::user();

    	$user->update($data);
    	return back()->with('script_success', 'Profile updated.');
    }

    public function settings ()
    {
        $brukerinfo = Auth::user();
        $topNav = [
            0 => [
                'url'  => '/user/settings',
                'name' => 'Settings'
            ]
        ];
    	return view('user.settings', [
            'topNav' => $topNav,
            'brukerinfo' => $brukerinfo
        ]);
    }
}
