<?php

namespace App\Http\Controllers\Auth;

use App\Models\StudentLogIn;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * The login username field.
     *
     * @return string
     */
    public function username()
    {
        return 'student_login_name';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Attempt login against students_logins table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $user = StudentLogIn::where('student_login_name', $request->input($this->username()))->first();

        if (! $user) {
            return false;
        }

        $plainPassword = (string) $request->input('password');
        $storedPassword = (string) $user->student_login_password;

        $passwordMatches = hash_equals($storedPassword, $plainPassword);

        if (! $passwordMatches) {
            try {
                $passwordMatches = Hash::check($plainPassword, $storedPassword);
            } catch (RuntimeException $e) {
                $passwordMatches = false;
            }
        }

        if (! $passwordMatches) {
            return false;
        }

        $this->guard()->login($user, $request->boolean('remember'));

        return true;
    }
}
