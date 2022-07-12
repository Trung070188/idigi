<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDevice;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


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
                $totalDevice = 0;
                $check = 0;
                if($user->user_devices){
                    foreach ($user->user_devices as $device){
                        $totalDevice ++;
                        if($device->device_uid == $request->device_unique){
                            $check = 1;
                        }
                    }
                }

                if($check == 0 && $totalDevice > 2){
                    return [
                        'code' => 3,
                        'message' => 'Bạn đã đăng ký quá nhiều thiết bị',
                    ];
                }else{
                    if($check == 0){
                            UserDevice::create([
                                'device_uid' => $request->device_unique,
                                'device_name' => $user->username,
                                'user_id' => $user->id,
                                'status' => 2,
                                'secret_key' => (Str::random(10))
                            ]);
                    }

                }


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
