<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleSignController
{
    public function login(Request $req)
    {
        $token = $req->token;

        try {
            $aud = googleDesktopClientId();
            $userInfo = curl_get_json('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token='.$token);

            if (isset($userInfo['email']) && $userInfo['aud'] === $aud) {
                /**
                 * @var User $user
                 */
                $user = User::where('email', $userInfo['email'])->first();


                if (!$user) {
                    return [
                        'code' => 2,
                        'message' => 'Đăng nhập thất bại',
                    ];
                }


                $user->last_login = date('Y-m-d H:i:s');

                if (!empty($userInfo['picture']) && $userInfo['picture'] != $user->avatar) {
                    $user->image = $userInfo['picture'];
                }

                $user->save();
                Auth::login($user);

                $payload = [
                    'email' => $user->email,
                    'username' =>$user->username,
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
