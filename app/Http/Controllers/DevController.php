<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Logit\User;
use Logit\NewMessage;

class DevController extends Controller
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

    public function showSession ()
    {
    	dd( session()->all() );
    }

    public function adminPanel ()
    {
    	if (Auth::user()->is_admin == 0) {
    		abort(403, 'Unauthorized action.');
    	}

    	$topNav = [
            0 => [
                'url'  => '#',
                'name' => 'Admin Panel'
            ]
        ];

    	return view('adminPanel', [
            'topNav'          => $topNav,
            'brukerinfo'      => Auth::user(),
        ]);
    }

    public function newMessage (Request $request)
    {
		$validated = true;
		foreach ($request->all() as $input) {
			if ($input == "") {
				$validated = false;
			}
		}

		if ($validated) {
	    	$users = User::where('verified', 1)
	    		->select('email', 'id')
	    		->get();
	    	
	    	foreach ($users as $user) {
		    	$message = new NewMessage;
		    	$message->title = $request->title;
		    	$message->type = $request->type;
		    	$message->confirmButtonText = $request->confirmButtonText;
		    	$message->html = $request->html;
		    	$message->user_id = $user->id;

		    	$message->save();
	    	}
	    	return back()->with('script_success', 'All messages has been added.');

		} else {
			return back()->with('script_danger', 'Please make sure all fields are set!');
		}
	} 
}