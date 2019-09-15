<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class CheckAdminRole
{

    public function handle($request, Closure $next , $ADMIN)
    {
			if(\Auth::check() != true){
				return redirect()->guest('login');
			}

		switch($ADMIN){
			case 99:
				$pass = chkMasterAdmin();
			break;
			case 1:
				$pass = chkAdmin();
			break;
			default:
				$pass = false;
		}


		if($pass){
			$response = $next($request);
			return $response;
		}else{
			abort(404);
      return new Response(view('apps.SYSTEM.denied'));
		}


    }
}
