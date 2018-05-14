<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\LogsController as activity;
use Auth;
use Redirect;
use App\AdminUser;
use Input;
use Mail;
use Session;
use Crypt;

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
    protected $redirectTo = 'admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->objAdminUser = new AdminUser();
    }

    public function index()
    {
        if(Auth::guard('admin')->check())
        {
            return Redirect::to('admin/dashboard');
        }
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {

	        Session::put("sesid", date('YmdHis-'.rand(0,99).'-admin'));
	        $message = "Admin Login";
	        $log     = array(
		        "type" => "login-admin",
		        "message" => $message
	        );

	        activity::addLog($log);

            return redirect('admin/dashboard');
        }

        return Redirect::back()->withInput()->withErrors(trans('validation.invalidcombo'));
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function logout(Request $request)
    {

	    if(Auth::guard('admin')->check()) {
		    $message = "Login : Admin loged out";

		    $log = array(
			    "type" => "login-admin",
			    "message" => $message
		    );

		    activity::addLog($log);

		    $this->guard()->logout();


	    }
	    return redirect('/admin');
    }
}
