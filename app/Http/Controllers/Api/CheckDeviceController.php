<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class CheckDeviceController extends Controller
{


    public function __construct()
    {

    }

    public function checkDevice(Request  $request){

        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->whereHas('user_devices', function ($q) use ($request){
                $q->where('device_uid', $request->device_unique);
            })
            ->first();

        if($user){

            return [
                'code' => 0,
                'msg' => 'Device Id đã tồn tại',

            ];

        }

        return [
            'code' => 1,
            'msg' => 'Device Id không  tồn tại',
        ];

    }
}
