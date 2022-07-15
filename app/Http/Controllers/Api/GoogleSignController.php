<?php

namespace App\Http\Controllers\Api;

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
                if ($user->user_devices) {
                    foreach ($user->user_devices as $device) {
                        $totalDevice++;
                        if ($device->device_uid == $req->device_unique) {
                            $check = 1;
                        }
                    }
                }

                if ($check == 0 && $totalDevice > 2) {
                    return [
                        'code' => 3,
                        'message' => 'Bạn đã đăng ký quá nhiều thiết bị',
                    ];
                } else {
                    if ($check == 0) {
                        UserDevice::create([
                            'device_uid' => $req->device_unique,
                            'device_name' => $req->device_name,
                            'user_id' => $user->id,
                            'status' => 2,
                            'secret_key' => (Str::random(10))
                        ]);
                    }

                }


                $user->last_login = date('Y-m-d H:i:s');

                if (!empty($userInfo['picture']) && $userInfo['picture'] != $user->avatar) {
                    $user->image = $userInfo['picture'];
                }

                $user->save();
                Auth::login($user);

                $payload = [
                    'email' => $user->email,
                    'username' => $user->username,
                    'expired' => strtotime(Carbon::now()->addHours(10))
                ];

                $jwt = JWT::encode($payload, env('SECRET_KEY_API'), 'HS256');

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
