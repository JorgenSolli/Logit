<?php

namespace Logit\Http\Controllers;

use Illuminate\Http\Request;

class DevController extends Controller
{
    public function showSession ()
    {
    	dd( session()->all() );
    }	
}