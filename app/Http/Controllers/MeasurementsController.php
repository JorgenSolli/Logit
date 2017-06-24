<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeasurementsController extends Controller
{
    public function measurements ()
    {
		$brukerinfo = Auth::user();
		$topNav = [
            0 => [
                'url'  => '/dashboard/measurements',
                'name' => 'Measurements'
            ]
        ];

    	return view('measurements', [
    		'brukerinfo' => $brukerinfo,
    		'topNav' 	 => $topNav
		]);
    }
}
