<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

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
        
        return view('dashboard', [
            'topNav'     => $topNav,
            'brukerinfo' => $brukerinfo
        ]);
    }

    
}
