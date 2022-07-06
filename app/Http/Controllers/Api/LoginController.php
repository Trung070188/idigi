<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class LoginController extends Controller
{


    public function __construct()
    {

    }

    public function Login(Request  $request){
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if($user){

            if(\Hash::check($request->password, $user->password)){
                $payload = [
                    'email' => $user->email,
                    'username' =>$user->username,
                    'expired' => strtotime(Carbon::now()->addHours(10))
                ];
                $jwt = JWT::encode($payload, env('SECRET_KEY_API'), 'HS256');

                return [
                    'code' => 0,
                    'msg' => 'Success',
                    'access_token' => $jwt
                ];
            }

        }

        return [
            'code' => 1,
            'msg' => 'Invalid username or password',
        ];

    }
}
