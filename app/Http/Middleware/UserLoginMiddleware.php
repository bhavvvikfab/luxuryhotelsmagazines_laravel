<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (Auth::check()) {
        //     // dd('hh');
        //     // Redirect to login page
        //     return $next($request);
        // }

        $token = $request->bearerToken();

        if ($token) {
            auth()->guard('api')->setRequest($request);

            if (Auth::guard('api')->check()) {
               
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized!','status'=>'fail']);
     
    }
}
