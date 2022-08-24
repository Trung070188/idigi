<?php

namespace App\Http\Controllers\Api;

use App\Models\AuthenticationLog;
use App\Models\School;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GoogleSignController
{
    public function login(Request $req)
    {
        $token = $req->token;

        try {
            $aud = googleDesktopClientId();
            $userInfo = curl_get_json('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $token);


            if (isset($userInfo['email']) && $userInfo['aud'] === $aud) {
                /**
                 * @var User $user
                 */
                $user = User::where('email', $userInfo['email'])
                    ->first();


                if (!$user) {
                    return [
                        'code' => 2,
                        'message' => 'Đăng nhập thất bại',
                    ];
                }


                $totalDevice = 0;
                $check = 0;
                $secret = '';
                $deviceID = '';
                $deviceName = '';
                if ($user->user_devices) {
                    foreach ($user->user_devices as $device) {
                        $totalDevice++;
                        if ($device->device_uid == $req->device_unique) {
                            $check = 1;
                            $secret = $device->secret_key;
                            $deviceID = $device->device_uid;
                            $deviceName = $device->device_name;
                        }
                    }
                }
                $allDevice = 0;
                $school = School::where('id', @$user->school_id)->first();
                if(@$school->license_to < Carbon::now()){
                    return [
                        'code' => 4,
                        'msg' => 'License expired',
                    ];
                }

                if($school){
                    $allDevice = $school->devices_per_user;
                }

                if ($check == 0 && $totalDevice >= $allDevice) {
                    return [
                        'code' => 3,
                        'message' => 'Bạn đã đăng ký quá nhiều thiết bị',
                    ];
                } else {
                    if ($check == 0) {
                        $secret = (Str::random(10));
                        $deviceID = $req->device_unique;
                        $deviceName =  $req->device_name;
                        UserDevice::create([
                            'device_uid' => $req->device_unique,
                            'device_name' => $req->device_name,
                            'user_id' => $user->id,
                            'status' => 2,
                            'secret_key' => $secret
                        ]);
                    }

                }


                $user->last_login = date('Y-m-d H:i:s');

                if (!empty($userInfo['picture']) && $userInfo['picture'] != $user->avatar) {
                    $user->image = $userInfo['picture'];
                }

                $user->save();
                Auth::login($user);

                if($school){
                    $expired = $school->license_to;
                }

                if(!$expired){
                    $expired = Carbon::now()->addHours(-10);
                }

                $payload = [
                    'email' => $user->email,
                    'username' => $user->username,
                    'user_id' => $user->id,
                    'device_uid' => $deviceID,
                    'device_name' => $deviceName,
                    'expired' => strtotime($expired),
                    'secret_key' => $secret,
                    'create_time' =>  Carbon::now()->timestamp
                ];

                $jwt = JWT::encode($payload, env('SECRET_KEY'), 'HS256');
                $authenticationLog=new AuthenticationLog();
                $authenticationLog->user_id=$user->id;
                $authenticationLog->device_uid=$deviceID;
                $authenticationLog->user_agent=$req->userAgent();
                $authenticationLog->ip_address=$req->getClientIp();
                $authenticationLog->login_at= Carbon::now();
                $authenticationLog->save();
                return [
                    'code' => 0,
                    'access_token' => $jwt,
                ];
            }
        } catch (\Exception $e) {
            Log::error($e);
        }

        return [
            'code' => 1,
            'message' => 'Đăng nhập thất bại',
        ];
    }
}
