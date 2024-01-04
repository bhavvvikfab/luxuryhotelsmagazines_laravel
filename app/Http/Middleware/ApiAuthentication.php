<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('Authorization');
        
        if($apiKey !== 'hXuRUGsEGuhGf6KM')
        {
            return response()->json(['message' => 'Unauthorized!','status'=>'fail']);
        }
        return $next($request);
    }
}
