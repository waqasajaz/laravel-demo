<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\LogsController as activity;
use Redirect;
use Session;
use Auth;
use Cache;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function authenticated()
    {
    	$user = Auth::user();

	    Session::put("sesid", date('YmdHis-'.rand(0,99).'-'.$user['id']));

	    $message = "Login : ".$user['name']." ".$user['lastname'];
	    $log     = array(
		    "type" => "login",
		    "message" => $message
	    );

	    activity::addLog($log);
    }

	public function logout(Request $request) {


		$user    = Auth::user();
		$message = "Logout : ".$user['firstname']." ".$user['lastname'];
		$log     = array(
			"type" => "login",
			"message" => $message,
			"userid"    => $user['id']
		);

		Auth::logout();

		Cache::flush();

		activity::addLog($log);

		return redirect('/');
	}

	public function getlogout()
	{
		return redirect('/');
	}
}


