<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Logit\Measurement;
use Logit\Settings;

class MeasurementController extends Controller
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
     * Displays the measurements view for the current user
     *
     * @return view
     */
    public function measurements ()
    {
		$brukerinfo = Auth::user();
        $settings = Settings::where('user_id', $brukerinfo->id)->first();

        if ($settings) {
            $unit = ($settings->unit === "Metric") ? "cm" : "in";
        } else {
            $unit = "cm";
        }

        $measurements = Measurement::where('user_id', $brukerinfo->id)->first();
        if (!$measurements) {
            $measurements = null;
        }

		$topNav = [
            0 => [
                'url'  => '/dashboard/measurements',
                'name' => 'Measurements'
            ]
        ];

    	return view('measurements', [
    		'brukerinfo'   => $brukerinfo,
            'measurements' => $measurements,
            'unit'         => $unit,
    		'topNav' 	   => $topNav
		]);
    }

    /**
     * Saves an entry with measurements for current user
     *
     * @return \Illuminate\Http\Response
     */
    public function saveMeasurements (Request $request)
    {
        $measurements = new Measurement;
        
        $measurements->user_id   = $user = Auth::id();
        $measurements->weight    = $request->weight;
        $measurements->body_fat  = $request->body_fat;
        $measurements->neck      = $request->neck;
        $measurements->shoulders = $request->shoulders;
        $measurements->arms      = $request->arms;
        $measurements->chest     = $request->chest;
        $measurements->waist     = $request->waist;
        $measurements->forearms  = $request->forearms;
        $measurements->calves    = $request->calves;
        $measurements->thighs    = $request->thighs;
        $measurements->hips      = $request->hips;
        
        $measurements->save();
        return back()->with('script_success', 'Profile updated.');
    }
}
