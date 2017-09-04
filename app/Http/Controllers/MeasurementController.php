<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Logit\Measurement;
use Logit\Settings;
use Carbon;

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
        $dateNow = Carbon\Carbon::now();
        $settings = Settings::where('user_id', $brukerinfo->id)->first();

        if ($settings) {
            $unit_distance = ($settings->unit === "Metric") ? "cm" : "in";
            $unit_weight = ($settings->unit === "Metric") ? "kg" : "pounds";
        } else {
            $unit_distance = "cm";
            $unit_weight = "kg";
        }

        $lastInput = Measurement::where('user_id', $brukerinfo->id)
            ->orderBy('date', 'DESC')
            ->first();

        if (!$lastInput) {
            $lastInput = null;
        }

        $measurements =  Measurement::where('user_id', $brukerinfo->id)
            ->orderBy('date', 'DESC')
            ->get();
            
		$topNav = [
            0 => [
                'url'  => '/dashboard/measurements',
                'name' => 'Measurements'
            ]
        ];

    	return view('measurements', [
    		'brukerinfo'    => $brukerinfo,
            'dateNow'       => $dateNow,
            'lastInput'     => $lastInput,
            'measurements'  => $measurements,
            'unit_distance' => $unit_distance,
            'unit_weight'   => $unit_weight,
    		'topNav' 	    => $topNav
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
        $measurements->date      = $request->date;
        
        $measurements->save();
        return back()->with('script_success', 'Profile updated.');
    }

    /**
     * Deletes an entry (measurement) for current user
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteMeasurement (Request $request)
    {
        $id = $request->id;

        $measurement = Measurement::where('id', $id)->first();

        if ($measurement->user_id === Auth::id()) {
            $measurement->delete();
            return "true";
        }
        else {
            return "false";
        }
    }
}
