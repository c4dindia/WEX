<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WallesterNotificationBasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !preg_match('/Basic\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $credentials = base64_decode($matches[1]);
        list($username, $password) = explode(':', $credentials, 2);

        // Validate sandbox credentials
        // if ($username !== 'wallester_sandbox' || $password !== 'wallester007') {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        
        // Validate production credentials
        if ($username !== 'wallester_production1' || $password !== 'wall555ester') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
