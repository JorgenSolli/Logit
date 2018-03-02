<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Logit\Measurement;
use Logit\Settings;
use Carbon\Carbon;

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
    public function index ()
    {
		$user = Auth::user();
        $dateNow = Carbon::now();
        $settings = Settings::where('user_id', $user->id)->first();

        if ($settings) {
            $unit_distance = ($settings->unit === "Metric") ? "cm" : "in";
            $unit_weight = ($settings->unit === "Metric") ? "kg" : "pounds";
        } else {
            $unit_distance = "cm";
            $unit_weight = "kg";
        }

        $lastInput = Measurement::where('user_id', $user->id)
            ->orderBy('date', 'DESC')
            ->first();

        if (!$lastInput) {
            $lastInput = null;
        }

        $measurements =  Measurement::where('user_id', $user->id)
            ->orderBy('date', 'DESC')
            ->get();
            
		$topNav = [
            0 => [
                'url'  => '/dashboard/measurements',
                'name' => 'Measurements'
            ]
        ];

    	return view('measurements', [
    		'user'          => $user,
            'dateNow'       => $dateNow,
            'lastInput'     => $lastInput,
            'measurements'  => $measurements,
            'unit_distance' => $unit_distance,
            'unit_weight'   => $unit_weight,
    		'topNav' 	    => $topNav
		]);
    }

    public function read ()
    {
        $result = array(
            'labels' => [],
            'series' => [
                [],
                [],
                [],
                [],
                [],
                [],
                [],
                [],
                [],
                [],
                [],
            ]
        );

        $measurements = Measurement::where('user_id', Auth::id())->get();

        foreach ($measurements as $measurement) {
            array_push($result['labels'], Carbon::parse($measurement->created_at)->format('d/m/y'));

            array_push($result['series'][0], $measurement->weight);
            array_push($result['series'][1], $measurement->arms);
            array_push($result['series'][2], $measurement->calves);
            array_push($result['series'][3], $measurement->body_fat);
            array_push($result['series'][4], $measurement->chest);
            array_push($result['series'][5], $measurement->thighs);
            array_push($result['series'][6], $measurement->neck);
            array_push($result['series'][7], $measurement->waist);
            array_push($result['series'][8], $measurement->hips);
            array_push($result['series'][9], $measurement->shoulders);
            array_push($result['series'][10], $measurement->forearms);
        }

        return $result;
    }

    /**
     * Saves an entry with measurements for current user
     *
     * @return \Illuminate\Http\Response
     */
    public function create (Request $request)
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
    public function delete (Request $request)
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
