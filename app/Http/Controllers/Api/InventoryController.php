<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateDownloadInventory;
use App\Models\AllocationContentSchool;
use App\Models\DownloadInventoryLog;
use App\Models\File;
use App\Models\Inventory;
use App\Models\Lesson;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class InventoryController extends Controller
{


    public function __construct()
    {

    }

    public function downloadInventory($id, Request  $request){

        $token = $request->bearerToken();

        $decoded = JWT::decode($token, new Key(env('SECRET_KEY'), 'HS256'));
        $user = User::where('username', $decoded->username)->first();
        $inventory = Inventory::where('id', $id)->with(['lessons'])->first();

        if($request->os == "Mac"){
            $file = File::find($inventory->file_asset_mac_id);
        }else{
            $file = File::find($inventory->file_asset_id);
        }

        $lessonId = NULL;

        if($inventory->lessons){
            foreach ($inventory->lessons as $lesson){
                $lessonId = $lesson->id;
            }
        }

        if($file){
            $this->dispatch(new UpdateDownloadInventory([
                'user_id' => $user->id,
                'ip_address' => $request->getClientIp(),
                'user_agent' => $request->userAgent(),
                'device_uid' => @$decoded->device_uid,
                'lesson_id' => $lessonId,
                'download_at' => Carbon::now(),
                'inventory_id' => $id
            ]));

            return redirect($file->url);

        }


        return 'Error';
    }
}
