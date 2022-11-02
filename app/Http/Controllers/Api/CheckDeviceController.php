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
            ->with(['user_devices','schools'])
            ->first();

        if($user){

            if($user->schools)
            {
                $countDevice=$user->schools->devices_per_user;
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
                    if($count > $countDevice){
                        return [
                            'code' => 2,
                            'msg' => 'Đã đủ ' .$countDevice.' device',

                        ];
                    }else{
                        return [
                            'code' => 1,
                            'msg' => 'Số device hiện tại là '.$count,

                        ];
                    }
                }

            }
            else
            {
                return [
                  'code'=> 0,
                  'msg'=>'Bạn không phải là school admin or teacher. '
                ];
            }




        }

        return [
            'code' => 3,
            'msg' => 'Username khong  tồn tại',
        ];

    }

    public function checkVersion(Request  $request){
        $curApp = AppVersion::where('type',$request->os)
            ->where('is_default', 1)->first();
        if(!$curApp){
            return [
                'code' => 1,
                'msg' => "Không tồn tại version",
                'results' => []
            ];
        }
        $isOta = 0;

        if($curApp->url_updated){
            $isOta = 1;
        }
        if($isOta==0)
        {
            return [
            'code' => 0,
            'msg' => "Success",
            'results' => [
                'latest_version' => $curApp->version,
                'is_ota' => $isOta,
                'link_version' => $curApp->url,
            ]
            ];
        }
        if($isOta=1)
        {
            return [
                'code' => 0,
                'msg' => "Success",
                'results' => [
                    'latest_version' => $curApp->version,
                    'is_ota' => $isOta,
                    'link_version' => $curApp->url_updated,
                ]
            ];
        }


    }
}
