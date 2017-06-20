<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Auth\Guard;
use Closure;

class TimeZone
{

     /**
     * The current logged in user instance
     * @var [type]
     */
    protected $user;

    /**
     * creates an instance of the middleware
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->user = $auth->user();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($this->user)
        {
            $timeZone = $this->user->timezone;
            if ($timeZone != "") {
                date_default_timezone_set($timeZone);
                return $next($request);
            }

            return $next($request);
        } 
        
        else 
        {
            // User not logged in and app.php will set the standard timezone ('UTC')
            return $next($request);
        }
    }

    
}
