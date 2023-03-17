<?php

namespace App\Http\Controllers\Auth;

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
            $aud = googleClientId();
            $userInfo = curl_get_json('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token='.$token);

            if (isset($userInfo['email']) && $userInfo['aud'] === $aud) {

                /**
                 * @var User $user
                 */
                $user = User::where('email', $userInfo['email'])->first();

                if (!$user) {
                    $data = [
                        'email' => @$userInfo['email'],
                        'username' => @$userInfo['email'],
                        'full_name' => @$userInfo['name'],
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $user = User::create($data);
                }

                Auth::login($user);

                return [
                    'code' => 200,
                    'redirect' => '/xadmin',
                ];
            }
        } catch (\Exception $e) {
            Log::error($e);
        }

        return [
            'code' => 1,
            'message' => 'Login fail',
        ];
    }
}
