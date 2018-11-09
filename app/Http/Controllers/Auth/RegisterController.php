<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Redirects the user to the dashboard after a successful sign in.
     * 
     * @return string
     */
    protected function redirectTo() {
        return route('dashboard.statistics', [ 'username' => Auth::user()->username ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * 
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|string|max:255',
            'email'    => 'bail|required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'username' => 'bail|string|max:75|alpha_num|unique:users,username',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * 
     * @return \App\User
     */
    public function create(array $data)
    {
        $username = array_key_exists('username', $data) ? $data['username'] : User::generateUsername();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'username' => $username
        ]);

        // Fire an event for the new registration of the user so the
        // listeners can perform their task such as assigning role or
        // mailing the user.
        event(new UserRegistered($user));

        return $user;
    }
}
