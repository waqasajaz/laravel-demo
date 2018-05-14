<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Session;
use Auth;
use Redirect;

class AuthenticateAdmin
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {

        if (Auth::guard('admin')->user())
        {

        }
        else
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                return redirect('admin');
            }
        }
        return $next($request);
    }
}