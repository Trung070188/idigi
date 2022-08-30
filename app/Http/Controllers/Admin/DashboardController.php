<?php
/**
 * @author
 * @date: 3:20 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\School;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\Xlogger;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends AdminBaseController
{

    public function index()
    {
        $title = 'Thống kê';
        $component = 'DashboardIndex';
        $users = User::query()->orderBy('id', 'desc')->count();
        $devices = UserDevice::query()->orderBy('id', 'desc')->count();
        $schools = School::query()->orderBy('id', 'desc')->count();
        $lessons = Lesson::query()->orderBy('id', 'desc')->count();



//        $dt      = Carbon::create(2022, 8, 20, 0);
        $today = Carbon::now();

//        ($today->diffInDays($dt));
        $licenses = School::query()->whereNotNull('license_to')->orderBy('id', 'ASC')->get()->toArray();
        $licenseRemain = [];

        foreach ($licenses as $license) {
            if ($today->diffInDays($license['license_to']) <= 15) {
                $license['dayEnd'] = $today->diffInDays($license['license_to']);
                $LicenseId = $license['id'];
                $license['url'] = url("xadmin/schools/editLicense?id= $LicenseId");
                $licenseRemain[] = $license;
            }
        }
        $jsonData = [
            'users' => $users,
            'devices' => $devices,
            'schools' => $schools,
            'lessons' => $lessons,
            'licenseRemain' => $licenseRemain,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    public function data(Request $req)
    {

        $xloggers = Xlogger::query()->where('request_uri', '=', '/xadmin/schools/save')
            ->orWhere('request_uri','=','/xadmin/schools/remove')
            ->orWhere('request_uri','=','/xadmin/users/save')
            ->orWhere('request_uri','=','/xadmin/users/remove')
            ->orWhere('request_uri','/xadmin/roles/save')
            ->orWhere('request_uri','=','/xadmin/roles/remove')
            ->orWhere('request_uri','/xadmin/allocation_contents/save')
            ->orWhere('request_uri','/xadmin/schools/saveLicense')
            ->orderBy('id', 'ASC')->get();
//
//        if ($req->keyword) {
//            $query->where('label', 'LIKE', '%' . $req->keyword . '%');
//        }
//
//        if ($req->label) {
//            $query->where('label', 'LIKE', '%' . $req->label . '%');
//        }
//
//        $limit = 25;
//
//        if ($req->limit) {
//            $limit = $req->limit;
//        }
//        $data = [];

        $xlogger = [];
        foreach ($xloggers as $entry) {

                if($entry['request_uri']=='/xadmin/schools/save'
                    || $entry['request_uri']=='/xadmin/roles/save'
                    || $entry['request_uri']=='/xadmin/schools/toggleStatus'
                    || $entry['request_uri']=='/xadmin/schools/remove'
                    ||  $entry['request_uri']=='/xadmin/roles/remove'
                    || $entry['request_uri']='/xadmin/allocation_contents/save'
                    ||$entry['request_uri']='/xadmin/allocation_contents/remove'

                )

                {
                    $dataXlogger = json_decode($entry['response'], TRUE);
                    $actionName = @$dataXlogger['actionName'];
                    $entry['actionName'] = $actionName;
                    $entry['status'] = @$dataXlogger['status'];
                }

                if($dataXlogger['code']==0)
                {
                    $xlogger[]=[
                        'username'=>$entry['username'],
                        'actionName'=>@$entry['actionName'],
                        'status'=>@$entry['status']
                    ];

                }

        }

        return [
            'code' => 0,
            'data' =>$xlogger,

        ];
    }


}
