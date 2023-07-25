<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerification {

	public function handle(Request $request, Closure $next): Response {
        
		$token  = $request->cookie('token');
		$result = JWTToken::VerifyToken($token);

		if ('unauthorized' == $result) {
			return redirect('/userLogin');
		}

		$request->headers->set('email', $result->userEmail);
		$request->headers->set('id', $result->userID);

		return $next($request);
	}
}
