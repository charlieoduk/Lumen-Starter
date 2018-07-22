<?php

namespace Test\Mocks;
use Closure;
use Firebase\JWT\ExpiredException;

use App\Http\Middleware\JwtMiddleware;

class JwtMiddlewareMock extends JwtMiddleware {

    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');
        
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Authentication Token not provided.'
            ], 401);
        }
        if ($token === 'expired') {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } 
        if ($token === 'wrongtoken') {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        } else {
            return $next($request);
        }
    }
}
