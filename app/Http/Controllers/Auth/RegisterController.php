<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Mail\PleaseConfirmYourEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;


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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        do {
            $token = str_random(25);
        } while (User::where('confirmation_token', $token)->exists());
        return User::forceCreate([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'confirmation_token' => str_random(25),
            //to not do the do while process
            //'confirmation_token' => str_limit(md5($data['email'] . str_random()), 25, '')
            //i don't understand how this code sets it back to null
        ]);
    }

    protected function registered(Request $request, $user)
    {
        //cannot find RegisteredUsers, but he takes this function from there to create it
        //sendconfirmationemailrequest is also here
        //no event just the user...
        Mail::to($user)->send(new PleaseConfirmYourEmail($user));
        return redirect($this->redirectPath());
    }

}
