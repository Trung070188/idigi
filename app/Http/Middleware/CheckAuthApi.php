<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\School;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

            $decoded = JWT::decode($token, new Key(env('SECRET_KEY'), 'HS256'));

            $user = User::where('username', $decoded->username)->first();
            $school = School::where('id', @$user->school_id)->first();
            if(@$school->license_to < Carbon::now()){
                return response([
                    'code' => 1,
                    'msg' => 'Invalid username or password',
                ]);
            }
            $roles = $user->roles;
            $check = 0;

            if($roles){
                foreach ($roles as $role){
                    if($role->role_name == 'Teacher' || $role->role_name == 'Super Administrator' || $role->role_name == 'Administrator'){
                        $check = 1;
                    }
                }

            }

            if ($check == 0) {
                return response([
                    'code' => 2,
                    'message' => 'Bạn không có quyền truy cập',
                ]);
            }


            return $next($request);

        }catch (\Exception $e){

        }

        return response([
            'message' => 'Unauthenticated',
            'code' => 1,
        ], 403);


    }
}
