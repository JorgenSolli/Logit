<?php

namespace Logit\Http\Controllers\Auth;

use Logit\User;
use Logit\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    use VerifiesUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register/success';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*$this->middleware('guest');*/
        $this->middleware('guest', ['except' => ['getVerification', 'getVerificationError', 'resend']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        UserVerification::generate($user);

        UserVerification::send($user, 'Welcome to Logit!');

        $userData = [
            'email' => $user->email,
            'name'  => $user->name,
        ];

        return $this->registered($request, $user)
                   ?: redirect($this->redirectPath())->with($userData);
    }

    /**
     * Displays the users newly registert Email with a custom message.
     *
     * @param  \Logit\User  $user
     * @return \Illuminate\Http\Response
     */
    public function checkEmail ()
    {
        return view('auth.checkEmail');
    }

    /**
     * Resends the confirm email to the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend (Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user->verified === 0) {

            UserVerification::generate($user);
            UserVerification::send($user, 'Welcome to Logit!');

            $userData = [
                'email' => $user->email,
                'name'  => $user->name,
            ];

            $request->session()->flash('script_success', 'Email sent!');
            return view('welcome');
        }
    }
}
