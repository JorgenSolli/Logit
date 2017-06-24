<?php

namespace Logit\Http\Middleware;

use Illuminate\Contracts\Auth\Guard;
use Closure;
use Logit\Settings;

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
    public function __construct(Guard $auth, Settings $settings)
    {
        $this->user = $auth->user();
        
        $id = $this->user->id;
        $this->timezone = Settings::where('user_id', $id)->first()->timezone;
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
            $timezone = $this->timezone;
            if ($timezone != "") {
                date_default_timezone_set($timezone);
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
