<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class CheckLoginStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next )
    {
		if(\Auth::check()){
			if(\Auth::user()->status == 0){ // Removed
				\Auth::logout();
				return redirect('/login')->withErrors(['login_status' => 'This Account is no longer available.']);
			}
		}
		return $next($request);
    }
}
