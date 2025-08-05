<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class APITokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the Authorization header is present
        $authorizationHeader = $request->header('Authorization');

        if (is_null($authorizationHeader)) {
            return response()->json(['error' => 'Authorization header missing'], 401);
        }

        // Check if the header starts with 'Bearer '
        if (strpos($authorizationHeader, 'Bearer ') !== 0) {
            return response()->json(['error' => 'Invalid token format'], 401);
        }

        $token = str_replace('Bearer ', '',$authorizationHeader);

        if(!User::where('api_token',$token)->exists()){
            return response()->json(['token'=>$token,'error' => 'Unauthorized Token Used'], 401);
        }

        return $next($request);
    }

    /**
     * Validate the token.
     *
     * @param string $token
     * @return bool
     */
    protected function isValidToken($token)
    {
        // if(POnePaymentMethod::where('b_token',$token)->where('status','1')->exists()){
        //     return true;
        // }

        // if(PTwoPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
        //     return true;
        // }

        // if(PThreePaymentMethod::where('b_token',$token)->where('status','1')->exists()){
        //     return true;
        // }

        // if(PFourPaymentMethod::where('b_token',$token)->where('status','1')->exists()){
        //     return true;
        // }

        return false;
    }
}
