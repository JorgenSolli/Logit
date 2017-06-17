<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function viewSettings ()
    {
		$brukerinfo = Auth::user();
		
    	return view('settings');
    }
}
