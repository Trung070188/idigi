<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Providers\RouteServiceProvider;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $token = $request->bearerToken();

        try {

            JWT::decode($token, new Key(env('SECRET_KEY_API'), 'HS256'));
            return $next($request);

        }catch (\Exception $e){

        }

        return response([
            'message' => 'Unauthenticated',
            'code' => 1,
        ], 403);


    }
}
