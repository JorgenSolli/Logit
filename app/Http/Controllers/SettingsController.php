<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timezone');
    }
    
    public function viewSettings ()
    {
		$brukerinfo = Auth::user();
		
    	return view('settings');
    }
}
