<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginEmailVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $credentials = request(['email', 'password']);
        
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            
            if (isset($user->email_verified_at)) {
                return $response;
            }

            return response()->json(['message'=>"Email not verified"], 403);
        }

        return response()->json(['message'=>'Unauthorized'], 401);
    }
}
