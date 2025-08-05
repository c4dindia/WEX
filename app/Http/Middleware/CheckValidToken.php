<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckValidToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = request()->bearerToken();
        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token is missing'
            ], 401); // If no token is provided, return 401 Unauthorized
        }

        $token = PersonalAccessToken::findToken($accessToken);

        if (! $token || ! $token->tokenable) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'], 401);
        }

        $expiresAt = $token->expires_at;
        if ($expiresAt && Carbon::parse($expiresAt)->isPast()) {
            $token->delete();

            return response()->json([
                'success' => false,
                'message' => 'Token has expired'
            ], 401);
        }

        $user = $token->tokenable;
        if ($user->is_admin != '3' || $user->status != "1") {
            return response()->json([
                'success' => false,
                'message' => 'User does not have access'], 403);
        }

        Auth::setUser($user);

        return $next($request);
        }
}
