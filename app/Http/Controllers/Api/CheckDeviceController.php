<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
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
            ->with(['user_devices'])
            ->first();

        if($user){
            $count = 0;
            if($user->user_devices){
                foreach ($user->user_devices as $device){
                    $count ++ ;

                    if($device->device_uid == $request->device_unique){
                        return [
                            'code' => 0,
                            'msg' => 'Device Id đã tồn tại',

                        ];
                    }
                }
                if($count > 2){
                    return [
                        'code' => 2,
                        'msg' => 'Đã đủ 3 device',

                    ];
                }else{
                    return [
                        'code' => 1,
                        'msg' => 'Số device hiện tại là '.$count,

                    ];
                }
            }


        }

        return [
            'code' => 3,
            'msg' => 'Username khong  tồn tại',
        ];

    }

    public function checkVersion(Request  $request){
        $curApp = AppVersion::where('type', $request->os)
            ->where('is_default', 1)->first();
        if(!$curApp){
            return [
                'code' => 1,
                'msg' => "Không tồn tại version",
                'results' => []
            ];
        }

        return [
            'code' => 0,
            'msg' => "Success",
            'results' => [
                'latest_version' => $curApp->version,
                'link_version' => $curApp->url,
            ]
        ];
    }
}
