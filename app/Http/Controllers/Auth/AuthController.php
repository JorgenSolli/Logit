<?php

namespace Logit\Http\Controllers\Auth;

use Logit\Http\Controllers\Controller;
use Logit\User;

use Auth;
use Socialite;
use Validator;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
	/**
     * Determines is this is the users first login or not.
     *
     * @var boolean
     */
    protected $newOrNot = false;

    /**
     * Redirect the user to the OAuth Provider
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider ($provider)
    {
    	return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider. Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Othervise, create a new user then log them in. After
     * that, redirect them to the authenticated user to their dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback ($provider)
    {
    	/* Facebook requires us to force first and last name data */
		if ($provider == 'facebook') {
			try {
	        	$user = Socialite::driver($provider)
	        	->fields([
	        		'first_name',
	        		'last_name',
	        		'email',
        		])
	        	->user();
	        } catch (Exception $e) {
	            return redirect('auth/'.$provider);
	        }
		} else {
			try {
	        	$user = Socialite::driver($provider)
	        	->user();
	        } catch (Exception $e) {
	            return redirect('auth/'.$provider);
	        }
		}
        
        $authUser = $this->findOrCreateUser ($user, $provider);

        // Set second parameter to TRUE to endable remember token
        // Use this to implement the REMEMBER ME function
        Auth::login($authUser);
        
        /* If the user is new, redirect to settings page */
        if ($this->newOrNot == true) {
            // We`re not doing anything with this yet
        }
        
        return redirect("/dashboard");
    }

    /**
     * If a user has registered before using social auth, return user.
     * Else, create a new user object.
     * 
     * @param  $user Socialite user object
     * @param  $provider Social auth provider
     * @return User
     */
    public function findOrCreateUser ($user, $provider)
    {
    	$authUser = User::where('provider_id', $user->id)->first();

    	if ($authUser) {
    		return $authUser;
    	}

        /* The user is a new user, and will be redirected to the settings page */
        $this->newOrNot = true;
    	
    	if ($provider == 'google') {
    		$name = $user->user['name']['givenName'] . " " . $user->user['name']['familyName'];
            $avatar = $user->avatar_original;
    	} 
        elseif ($provider == 'linkedin') {
    		$name = $user->user['firstName'] . " " . $user->user['lastName'];
            $avatar = "";
    	} 
        elseif ($provider == 'facebook') {
            $name = $user->user['first_name'] . " " . $user->user['last_name'];
            $avatar = "";
        } 
        else {
    		$name = "";
            $avatar = "";
    	}

    	return User::create([
    		'name'		   => $name,
    		'email'        => $user->email,
    		'provider'     => $provider,
    		'provider_id'  => $user->id,
    		'avatar'       => $avatar,
    		'verified'     => 1,
		]);
    }
}
