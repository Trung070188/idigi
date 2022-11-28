<?php
/**
 * @author
 * @date: 3:20 PM
 */

namespace App\Http\Controllers\Admin;


use App\Console\Commands\DownloadLesson;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Models\DownloadAppLog;
use App\Models\DownloadLessonLog;
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
                $license['url'] = url("xadmin/schools/edit?id= $LicenseId");
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
       $dataAll=($req->all());
        $requestUris=Xlogger::query()
            ->where('request_uri', '=', '/xadmin/schools/save')
            ->orwhere('request_uri', '=', '/xadmin/schools/remove')
            ->orWhere('request_uri', '=', '/xadmin/users/save')
            ->orWhere('request_uri', '=', '/xadmin/users/remove')
            ->orWhere('request_uri', '/xadmin/users/saveTeacher')
            ->orWhere('request_uri', '/xadmin/roles/save')
            ->orWhere('request_uri', '=', '/xadmin/roles/remove')
            ->orWhere('request_uri', '/xadmin/allocation_contents/save')
            ->orWhere('request_uri', '/xadmin/schools/saveLicense')
            ->orWhere('request_uri', '/xadmin/plans/remove')
            ->orWhere('request_uri', '/xadmin/plans/save')
            ->orWhere('request_uri', '/xadmin/plans/saveDevice')
            ->orWhere('request_uri', '/xadmin/plans/addPackageLesson')
            ->orWhere('request_uri', '/xadmin/plans/planLesson')
            ->orWhere('request_uri', '/xadmin/plans/removeAllLesson')
            ->orWhere('request_uri', '/xadmin/plans/deleteLesson')
            ->orWhere('request_uri', '/xadmin/plans/deletePackageLesson')
            ->orWhere('request_uri', '/xadmin/user_devices/save')
            ->orWhere('request_uri', '/xadmin/user_devices/remove')->get();
        $request_uri=[];
        foreach ($requestUris as $requestUri)
        {
            $request_uri[]=$requestUri->request_uri;
        }
      $xlogerDay=[];
        $timeXlogs=Xlogger::where('http_code',200)->whereIn('request_uri',$request_uri)->orderBy('id','desc')->get();
        foreach ($timeXlogs as $timeXlog)
        {

            $time=Carbon::createFromFormat('Y-m-d H:i:s',$timeXlog->time )->format('Y-m-d');
            if($time==$req->created)
            {
                $xlogerDay[]=$timeXlog->id;
            }
        }
        $xloggers=Xlogger::where('http_code',200)->whereIn('id',$xlogerDay)->whereIn('request_uri',$request_uri)->orderBy('id','desc')->get();

        $xlogger = [];
        foreach ($xloggers as $entry) {
            $dataXlogger = json_decode($entry['response'], TRUE);
            $object = @$dataXlogger['object'];
            $entry['object'] = $object;
            $entry['status'] = @$dataXlogger['status'];
            $entry['role']=@$dataXlogger['role'];

            if(@$dataXlogger['code']==0)
            {
                $xlogger[]=[
                    'id'=>$entry->id,
                    'username'=>$entry['username'],
                    'object'=>@$entry['object'],
                    'status'=>@$entry['status'],
                    'role'=>@$entry['role'],
                    'ip'=>$entry['ip'],
                    'time'=>$entry['time']
                ];

            }

        }
        $year =(int)$dataAll['year'];
        $months=[1,2,3,4,5,6,7,8,9,10,11,12];
        foreach ($months as $month)
        {
            $dates[] = \Carbon\Carbon::parse($year."-".$month."-01");

        }
        foreach ($dates as $date)
        {
            $arrDate[]=[
//                   'date'=>$date->format('M'), // date => jan,feb,
                'date'=>$date->format('M'),
                'start'=>  $date->startOfMonth()->format('Y-m-d H:i:s'),
                'end'=>$date->endOfMonth()->format('Y-m-d H:i:s')
            ];
        }

         foreach ($arrDate as $date)
        {
           $downloadLog[]=[

               'downloadApp'=>DownloadAppLog::whereBetween('download_at',array($date['start'],$date['end']))->get(),
               'downloadLesson'=> DownloadLessonLog::whereBetween('created_at',array($date['start'],$date['end']))->get(),
               'month'=>$date['date']
               ];
        }
        $dataChart[]=['Year', 'Download app','Download lesson'];
        foreach ($downloadLog as $down)
       {
            $dataChart[]=[
              $down['month'],
                count($down['downloadApp']),
                count($down['downloadLesson'])
            ];
        }

        return [
            'code' => 0,
            'data' =>$xlogger,
            'dataChart'=>$dataChart

        ];
    }
    public function remove(Request $req)
    {
        $id=$req->id;
        Xlogger::where('id',$id)->delete();
        return [
          'code'=>0,
          'message'=>'Đã xóa'
        ];
    }


}
