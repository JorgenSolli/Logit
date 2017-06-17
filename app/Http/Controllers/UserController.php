<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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

    	/*$user->yob = $data['yob'];
    	$user->country = $data['country'];
    	$user->gender = $data['gender'];
    	$user->email = $data['email'];
    	$user->name = $data['name'];
    	$user->goal = $data['goal'];*/

    	$user->update($data);
    	return back()->with('success', 'Profile updated.');
    }

    public function settings ()
    {
    	
    }
}
