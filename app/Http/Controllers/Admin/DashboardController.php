<?php
/**
 * @author
 * @date: 3:20 PM
 */

namespace App\Http\Controllers\Admin;


use App\Console\Commands\DownloadLesson;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Models\AuthenticationLog;
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
        $today = Carbon::now();
        $licenses = School::query()->whereNotNull('license_to')->orderBy('id', 'ASC')->get();
        $licenseRemain = [];
        foreach ($licenses as $license) {
            if (($today->diffInDays($license->license_to)+1) <= 15 || ($today>$license->license_to)==true) {
                if( ($today>$license->license_to)==true)
                {
                    $license['dayEnd']=0;
                }
               else{
                   $license['dayEnd'] = $today->diffInDays($license['license_to'])+1;
               }
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
        $start_date=Carbon::createFromFormat('Y-m-d',$req->created)->format('Y-m-d 0:0:0');
        $end_date=Carbon::createFromFormat('Y-m-d',$req->created)->format('Y-m-d 23:59:59');

        $requestUris = Xlogger::query()->where('http_code',200)
            ->whereIn('request_uri', [
                '/xadmin/schools/save',
                '/xadmin/schools/remove',
                '/xadmin/users/save',
                '/xadmin/users/remove',
                '/xadmin/users/saveTeacher',
                '/xadmin/roles/save',
                '/xadmin/roles/remove',
                '/xadmin/allocation_contents/save',
                '/xadmin/schools/saveLicense',
                '/xadmin/plans/remove',
                '/xadmin/plans/save',
                '/xadmin/plans/saveDevice',
                '/xadmin/plans/addPackageLesson',
                '/xadmin/plans/planLesson',
                '/xadmin/plans/removeAllLesson',
                '/xadmin/plans/deleteLesson',
                '/xadmin/plans/deletePackageLesson',
                '/xadmin/user_devices/save',
                '/xadmin/user_devices/savesend',
                '/xadmin/users/removeDevice',
                '/xadmin/users/refuseDevice',
                '/xadmin/user_devices/remove'
            ])->whereBetween('time',[$start_date,$end_date]);
        $limit = 1;
        if ($req->limit) {
            $limit = $req->limit;
        }
        $requestUris = $requestUris->paginate($limit);
        $logAuths=AuthenticationLog::query()->whereBetween('login_at',[$start_date,$end_date]);
        $logAuths = $logAuths->paginate($limit);
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
        $logAu=[];
        foreach ($logAuths as $logAuth)
        {
            $user=User::with(['roles'])->where('id',$logAuth->user_id)->first();
            if(@$user->roles)
            {
                foreach($user->roles as $role)
                {
                    $roleName=$role->role_name;
                }
            }

           $logAu[]=[
               'id'=>$logAuth['id'],
               'username'=>@$user['username'],
               'object'=>'',
               'status'=>'Login',
               'role'=>@$roleName,
               'ip'=>$logAuth['ip_address'],
               'time'=>$logAuth['login_at']


           ];
        }

        $xlogger = [];
        foreach ($xloggers as $entry) {


                $dataXlogger = json_decode($entry['response'], TRUE);
                $object = @$dataXlogger['object'];
                $entry['object'] =@ $object;
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
                        'ip'=>$entry['ip'] ,
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
            'logAu'=>$logAu,
            'dataChart'=>$dataChart,
            'paginate' => [
                'currentPage' => $requestUris->currentPage(),
                'lastPage' => $requestUris->lastPage(),
                'totalRecord' =>$requestUris->count(),
            ]

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
